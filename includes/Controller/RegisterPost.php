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
 * @author Szilágyi Balázs
 */
class RegisterPost implements ControllerInterface
{
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
			'language' => Registry::get('language'),
			'first' => Registry::get('NOW'),
			'last' => Registry::get('NOW'),
			'groups' => Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id != '' ?: 0,
		);

		$task = Request::getPost('task');
		$solution = Request::getPost('solution');

		/* validate post */

		if (!$postData['name'])
		{
			$errorData = Language::get('name_empty');
		}
		if (!$postData['user'])
		{
			$errorData = Language::get('user_empty');
		}
		if (!$postData['email'])
		{
			$errorData = Language::get('email_empty');
		}
		else if ($emailValidator->validate($postData['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorData = Language::get('email_incorrect');
		}
		if ($loginValidator->validate($postData['user']) == Validator\ValidatorInterface::FAILED)
		{
			$errorData = Language::get('user_incorrect');
		}
		if ($captchaValidator->validate($task, $solution) == Validator\ValidatorInterface::FAILED)
		{
			$errorData = Language::get('captcha_incorrect');
		}
		if (Db::forTablePrefix('users')->where('user', $postData['user'])->findOne()->id)
		{
			$errorData = Language::get('user_exists');
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
		if (Registry::get('usersNew') == 0 && Db::getSettings('verification') == 1)
		{
			$successData['status'] = 0;
			$successData = Language::get('registration_verification');
		}
		else
		{
			$successData['status'] = 1;
			$successData = Language::get('registration_sent');
		}

		/* send login information */

		$routeLogin = Registry::get('root') . '/' . Registry::get('rewriteRoute') . 'login';

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
		$subject = Language::get('registration');
		$bodyArray = array(
			'<strong>' . Language::get('name') . Language::get('colon') . '</strong> ' . $successData['name'],
			'<br />',
			'<strong>' . Language::get('user') . Language::get('colon') . '</strong> ' . $successData['user'],
			'<br />',
			'<strong>' . Language::get('password') . Language::get('colon') . '</strong> ' . $successData['password'],
			'<br />',
			'<strong>' . Language::get('login') . Language::get('colon') . '<strong> ' . $linkElement
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
		echo $messenger->setAction(Language::get('login'), 'login')->doRedirect()->success($successData, Language::get('operation_completed'));
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
		echo $messenger->setAction(Language::get('back'), 'registration')->error($errorData, Language::get('error_occurred'));
	}
}