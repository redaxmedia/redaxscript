<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Hash;
use Redaxscript\Html\Element;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Filter;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

/**
 * children class to reset password post request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class ResetPost implements ControllerInterface
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
		$captchaValidator = new Validator\Captcha();

		/* process post */

		$postArray = array(
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* fetch user */

		$user = Db::forTablePrefix('users')->where(array(
			'id' => $postArray['id'],
			'password' => $postArray['password'],
			'status' => 1
		))->findOne();

		/* validate post */

		if (!$postArray['id'] || !$postArray['password'])
		{
			$errorArray[] = $this->_language->get('input_incorrect');
		}
		else if (!$user->id)
		{
			$errorArray[] = $this->_language->get('access_no');
		}
		if ($captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('captcha_incorrect');
		}

		/* handle error */

		if ($errorArray)
		{
			return $this->error($errorArray);
		}

		/* handle success */

		else
		{
			$password = uniqid();
			$resetArray = array(
				'id' => $user->id,
				'password' => $password
			);
			$mailArray = array(
				'name' => $user->name,
				'email' => $user->email,
				'password' => $password
			);

			/* reset and mail */

			if ($this->reset($resetArray) && $this->mail($mailArray))
			{
				return $this->success();
			}
			else
			{
				return self::error($this->_language->get('something_wrong'));
			}
		}
	}

	/**
	 *
	 * reset the password
	 *
	 * @since 3.0.0
	 *
	 * @param $resetArray
	 *
	 * @return boolean
	 */

	public function reset($resetArray = array())
	{
		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init($resetArray['password']);

		/* reset password */

		return Db::forTablePrefix('users')
			->where(array(
				'id' => $resetArray['id'],
				'status' => 1
			))
			->findOne()
			->set('password', $passwordHash->getHash())
			->save();
	}

	/**
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return boolean
	 */

	public function mail($mailArray = array())
	{
		$routeLogin = $this->_registry->get('root') . '/' . $this->_registry->get('rewriteRoute') . 'login';

		/* html elements */

		$linkElement = new Element();
		$linkElement
			->init('a', array(
				'href' => Registry::get('rewriteRoute') . $routeLogin,
				'class' => 'link-result'
			))
			->text($routeLogin);

		/* prepare message */

		$toArray = array(
			$mailArray['name'] => $mailArray['email']
		);
		$fromArray = array(
			Db::getSettings('author') => Db::getSettings('email')
		);
		$subject = $this->_language->get('password_new');
		$bodyArray = array(
			'<strong>' . $this->_language->get('password_new') . $this->_language->get('colon') . '</strong> ' . $mailArray['password'],
			'<br />',
			'<strong>' . $this->_language->get('login') . $this->_language->get('colon') . '</strong> ' . $linkElement
		);

		/* send message */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}

	/**
	 * handle success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successArray
	 *
	 * @return string
	 */

	public function success($successArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('login'), 'login')->doRedirect()->success($this->_language->get('password_sent'), $this->_language->get('operation_completed'));
	}

	/**
	 * handle error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('back'), 'login/recover')->error($errorArray, $this->_language->get('error_occurred'));
	}
}