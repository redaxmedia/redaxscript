<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filter\Email;
use Redaxscript\Filter\Special;
use Redaxscript\Hash;
use Redaxscript\Html\Element;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Registry;
use Redaxscript\Validator\Captcha;
use Redaxscript\Validator\Login;
use Redaxscript\Validator\ValidatorInterface;

/**
 * children class for register post
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Szilágyi Balázs
 */

class RegisterPost implements ControllerAbstract
{
	/**
	 * process
	 *
	 * @since 3.0.0
	 */

	public function _process()
	{
		$specialFilter = new Special();
		$emailFilter = new Email();

		/* clean post */

		$name = $r['name'] = $specialFilter->sanitize($_POST['name']);
		$user = $r['user'] = $specialFilter->sanitize($_POST['user']);
		$email = $r['email'] = $emailFilter->sanitize($_POST['email']);
		$password = uniqid();
		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init($password);
		$r['password'] = $passwordHash->getHash();
		$r['language'] = Registry::get('language');
		$r['first'] = $r['last'] = NOW;
		$r['groups'] = Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id;
		if ($r['groups'] == '')
		{
			$r['groups'] = 0;
		}
		$task = $_POST['task'];
		$solution = $_POST['solution'];

		/* validate post */

		$loginValidator = new Login();
		$emailValidator = new Email();
		$captchaValidator = new Captcha();

		if ($name == '')
		{
			$error = Language::get('name_empty');
		}
		else if ($user == '')
		{
			$error = Language::get('user_empty');
		}
		else if ($email == '')
		{
			$error = Language::get('email_empty');
		}
		else if ($loginValidator->validate($user) == ValidatorInterface::FAILED)
		{
			$error = Language::get('user_incorrect');
		}
		else if ($emailValidator->validate($email) == ValidatorInterface::FAILED)
		{
			$error = Language::get('email_incorrect');
		}
		else if ($captchaValidator->validate($task, $solution) == ValidatorInterface::FAILED)
		{
			$error = Language::get('captcha_incorrect');
		}
		else if (Db::forTablePrefix('users')->where('user', $user)->findOne()->id)
		{
			$error = Language::get('user_exists');
		}
		else
		{
			if (USERS_NEW == 0 && Db::getSettings('verification') == 1)
			{
				$r['status'] = 0;
				$success = Language::get('registration_verification');
			}
			else
			{
				$r['status'] = 1;
				$success = Language::get('registration_sent');
			}

			/* send login information */

			$loginRoute = ROOT . '/' . REWRITE_ROUTE . 'login';
			$linkElement = new Element();
			$linkElement
				->init('a', array(
					'href' => $loginRoute
				))
				->text($loginRoute);

			$toArray = array(
				$name => $email
			);
			if (Db::getSettings('notification') == 1)
			{
				$toArray[Db::getSettings('author')] = Db::getSettings('email');
			}
			$fromArray = array(
				$author => $email
			);
			$subject = Language::get('registration');
			$bodyArray = array(
				'<strong>' . Language::get('name') . Language::get('colon') . '</strong> ' . $name,
				'<br />',
				'<strong>' . Language::get('user') . Language::get('colon') . '</strong> ' . $user,
				'<br />',
				'<strong>' . Language::get('password') . Language::get('colon') . '</strong> ' . $password,
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
				->set($r)
				->save();
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