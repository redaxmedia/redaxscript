<?php
namespace Redaxscript\Controller;

use Redaxscript\Auth;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Language;
use Redaxscript\Messenger;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

/**
 * children class to process login
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class LoginPost implements ControllerInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param Request $request instance of the registry class
	 */

	public function __construct(Registry $registry, Language $language, Request $request)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_request = $request;
	}

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 */

	public function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$passwordValidator = new Validator\Password();
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();
		$auth = new Auth($this->_request);

		/* process post */

		$postArray = array(
			'user' => $this->_request->getPost('user'),
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* find user */

		$users = Db::forTablePrefix('users');
		if ($emailValidator->validate($postArray['user']) === Validator\ValidatorInterface::FAILED)
		{
			$postArray['user'] = $specialFilter->sanitize($postArray['user']);
			$loginByEmail = 0;
			$users->where(array(
				'user' => $postArray['user'],
				'status' => 1
			));
		}
		else
		{
			$postArray['user'] = $emailFilter->sanitize($postArray['user']);
			$loginByEmail = 1;
			$users->where(array(
				'email' => $postArray['user'],
				'status' => 1
			));
		}

		/* fetch user */

		$user = $users->findOne();

		/* validate post */

		if (!$postArray['user'])
		{
			$errorArray[] = $this->_language->get('user_empty');
		}
		else if (!$loginByEmail && $loginValidator->validate($postArray['user']) === Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['password'])
		{
			$errorArray[] = $this->_language->get('password_empty');
		}
		else if (!$user->password)
		{
			$errorArray[] = $this->_language->get('user_no');
		}
		else if ($passwordValidator->validate($postArray['password'], $user->password) === Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('password_incorrect');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('captcha_incorrect');
		}

		/* handle error */

		if ($errorArray)
		{
			return $this->error($errorArray);
		}

		/* handle success */

		else if ($auth->login($user->id))
		{
			return $this->success();
		}
		return $this->error($this->_language->get('something_wrong'));
	}

	/**
	 * show success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function success()
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('continue'), 'admin')->doRedirect(0)->success($this->_language->get('logged_in'), $this->_language->get('welcome'));
	}

	/**
	 * show error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('back'), 'login')->error($errorArray, $this->_language->get('error_occurred'));
	}
}