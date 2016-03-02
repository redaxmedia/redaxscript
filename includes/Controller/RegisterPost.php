<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Hash;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

/**
 * children class to process a register post request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class RegisterPost implements ControllerInterface
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
	 * process
	 *
	 * @since 3.0.0
	 */

	public function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();

		/* process post */

		$postData = array(
			'name' => $specialFilter->sanitize($this->_request->getPost('name')),
			'user' => $specialFilter->sanitize($this->_request->getPost('user')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* validate post */

		if (!$postData['name'])
		{
			$errorData[] = $this->_language->get('name_empty');
		}
		if (!$postData['user'])
		{
			$errorData[] = $this->_language->get('user_empty');
		}
		else if ($loginValidator->validate($postData['user']) == Validator\ValidatorInterface::FAILED)
		{
			$errorData[] = $this->_language->get('user_incorrect');
		}
		else if (Db::forTablePrefix('users')->where('user', $postData['user'])->findOne()->id)
		{
			$errorData[] = $this->_language->get('user_exists');
		}
		if (!$postData['email'])
		{
			$errorData[] = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postData['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorData[] = $this->_language->get('email_incorrect');
		}
		if ($captchaValidator->validate($postData['task'], $postData['solution']) == Validator\ValidatorInterface::FAILED)
		{
			$errorData[] = $this->_language->get('captcha_incorrect');
		}

		/* handle error */

		if ($errorData)
		{
			return self::error($errorData);
		}

		/* handle success */

		else
		{
			$passwordHash = new Hash(Config::getInstance());
			$passwordHash->init(uniqid());
			return self::success(array(
				'name' => $postData['name'],
				'user' => $postData['user'],
				'email' => $postData['email'],
				'password' => $passwordHash->getHash(),
				'language' => $this->_registry->get('language'),
				'groups' => Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id
			));
		}
	}

	/**
	 * success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successData
	 *
	 * @return string
	 */

	public function success($successData = array())
	{
		if (!$this->_registry->get('usersNew') && Db::getSettings('verification'))
		{
			$successData['status'] = 0;
			$successTitle = $this->_language->get('registration_verification');
		}
		else
		{
			$successData['status'] = 1;
			$successTitle = $this->_language->get('registration_sent');
		}
		$routeLogin = $this->_registry->get('root') . '/' . $this->_registry->get('rewriteRoute') . 'login';

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement
			->init('a', array(
				'href' => $routeLogin
			))
			->text($routeLogin);

		/* prepare message */

		$toArray = array(
			$successData['name'] => $successData['email']
		);
		if (Db::getSettings('notification') == 1)
		{
			$toArray[Db::getSettings('author')] = Db::getSettings('email');
		}
		$fromArray = array(
			$successData['author'] => $successData['email']
		);
		$subject = $this->_language->get('registration');
		$bodyArray = array(
			'<strong>' . $this->_language->get('name') . $this->_language->get('colon') . '</strong> ' . $successData['name'],
			'<br />',
			'<strong>' . $this->_language->get('user') . $this->_language->get('colon') . '</strong> ' . $successData['user'],
			'<br />',
			'<strong>' . $this->_language->get('password') . $this->_language->get('colon') . '</strong> ' . $successData['password'],
			'<br />',
			'<strong>' . $this->_language->get('login') . $this->_language->get('colon') . '<strong> ' . $linkElement
		);

		/* send message */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* create user */

		Db::forTablePrefix('users')
			->create()
			->set(array(
				'name' => $successData['name'],
				'user' => $successData['user'],
				'email' => $successData['email'],
				'password' => $successData['password'],
				'language' => $successData['language'],
				'groups' => $successData['groups'],
				'status' => $successData['status']
			))
			->save();

		/* show success */

		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('login'), 'login')->doRedirect()->success($successData, $successTitle);
	}

	/**
	 * error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorData
	 *
	 * @return string
	 */

	public function error($errorData = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('back'), 'registration')->error($errorData, $this->_language->get('error_occurred'));
	}
}