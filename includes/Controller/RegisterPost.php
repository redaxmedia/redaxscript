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
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

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
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Request $request instance of the registry class
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Request $request, Registry $registry, Language $language)
	{
		$this->_request = $request;
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * process
	 *
	 * @since 3.0.0
	 */

	public function _process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();

		$password = uniqid();
		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init($password);

		/* process post */

		$postData = array(
			'name' => $specialFilter->sanitize(Request::getPost('name')),
			'user' => $specialFilter->sanitize(Request::getPost('user')),
			'email' => $emailFilter->sanitize(Request::getPost('email')),
			'password' => $passwordHash->getHash(),
			'language' => $this->_registry->get('language'),
			'first' => $this->_registry->get('now'),
			'last' => $this->_registry->get('now'),
			'groups' => Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id,
		);

		$task = Request::getPost('task');
		$solution = Request::getPost('solution');

		/* validate post */

		if (!$postData['name'])
		{
			$errorData = $this->_language->get('name_empty');
		}
		if (!$postData['user'])
		{
			$errorData = $this->_language->get('user_empty');
		}
		if (!$postData['email'])
		{
			$errorData = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postData['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorData = $this->_language->get('email_incorrect');
		}
		if ($loginValidator->validate($postData['user']) == Validator\ValidatorInterface::FAILED)
		{
			$errorData = $this->_language->get('user_incorrect');
		}
		if ($captchaValidator->validate($task, $solution) == Validator\ValidatorInterface::FAILED)
		{
			$errorData = $this->_language->get('captcha_incorrect');
		}
		if (Db::forTablePrefix('users')->where('user', $postData['user'])->findOne()->id)
		{
			$errorData = $this->_language->get('user_exists');
		}

		/* handle error */

		if ($errorData)
		{
			self::_error($errorData);
		}

		/* handle success */

		else
		{
			self::_success(array(
				'name' => $postData['name'],
				'user' => $postData['user'],
				'email' => $postData['email'],
				'password' => $postData['password'],
				'language' => $postData['language'],
				'first' => $postData['first'],
				'last' => $postData['last'],
				'groups' => $postData['groups']
			));
		}
	}

	/**
	 * success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successData
	 */

	public function _success($successData = array())
	{
		if ($this->_registry->get('usersNew') == 0 && Db::getSettings('verification') == 1)
		{
			$successData['status'] = 0;
			$successData = $this->_language->get('registration_verification');
		}
		else
		{
			$successData['status'] = 1;
			$successData = $this->_language->get('registration_sent');
		}

		/* send login information */

		$routeLogin = $this->_registry->get('root') . '/' . $this->_registry->get('rewriteRoute') . 'login';

		/* html element */

		$linkElement = new Html\Element();
		$linkElement
			->init('a', array(
				'href' => $routeLogin
			))
			->text($routeLogin);

		$toArray = array(
			$successData['name'] => $successData['email']
		);
		if (Db::getSettings('notification') == 1)
		{
			$toArray[Db::getSettings('author')] = Db::getSettings('email');
		}
		$fromArray = array(
			$author => $successData['email']
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

		/* mailer object */

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
				'first' => $successData['first'],
				'last' => $successData['last'],
				'groups' => $successData['groups'],
				'status' => $successData['status']
			))
			->save();

		$messenger = new Messenger();
		echo $messenger->setAction($this->_language->get('login'), 'login')->doRedirect()->success($successData, $this->_language->get('operation_completed'));
	}

	/**
	 * error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorData
	 */

	public function _error($errorData = array())
	{
		$messenger = new Messenger();
		echo $messenger->setAction($this->_language->get('back'), 'registration')->error($errorData, $this->_language->get('error_occurred'));
	}
}