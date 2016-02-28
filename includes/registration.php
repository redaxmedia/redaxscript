<?php
use Redaxscript\Language;

/**
 * registration post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Registration
 * @author Henry Ruhs
 */

function registration_post()
{
	/* clean post */

	$name = $r['name'] = clean($_POST['name'], 0);
	$user = $r['user'] = clean($_POST['user'], 0);
	$email = $r['email'] = clean($_POST['email'], 3);
	$password = substr(sha1(uniqid()), 0, 10);
	$passwordHash = new Redaxscript\Hash(Redaxscript\Config::getInstance());
	$passwordHash->init($password);
	$r['password'] = $passwordHash->getHash();
	$r['description'] = '';
	$r['language'] = Redaxscript\Registry::get('language');
	$r['first'] = $r['last'] = NOW;
	$r['groups'] = Redaxscript\Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id;
	if ($r['groups'] == '')
	{
		$r['groups'] = 0;
	}
	$task = $_POST['task'];
	$solution = $_POST['solution'];

	/* validate post */

	$loginValidator = new Redaxscript\Validator\Login();
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();

	if ($name == '')
	{
		$error = Redaxscript\Language::get('name_empty');
	}
	else if ($user == '')
	{
		$error = Redaxscript\Language::get('user_empty');
	}
	else if ($email == '')
	{
		$error = Redaxscript\Language::get('email_empty');
	}
	else if ($loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('user_incorrect');
	}
	else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('email_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('captcha_incorrect');
	}
	else if (Redaxscript\Db::forTablePrefix('users')->where('user', $user)->findOne()->id)
	{
		$error = Redaxscript\Language::get('user_exists');
	}
	else
	{
		if (USERS_NEW == 0 && Redaxscript\Db::getSettings('verification') == 1)
		{
			$r['status'] = 0;
			$success = Redaxscript\Language::get('registration_verification');
		}
		else
		{
			$r['status'] = 1;
			$success = Redaxscript\Language::get('registration_sent');
		}

		/* send login information */

		$loginRoute = ROOT . '/' . REWRITE_ROUTE . 'login';
		$loginLink = '<a href="' . $loginRoute . '">' . $loginRoute . '</a>';
		$toArray = array(
			$name => $email
		);
		if (Redaxscript\Db::getSettings('notification') == 1)
		{
			$toArray[Redaxscript\Db::getSettings('author')] = Redaxscript\Db::getSettings('email');
		}
		$fromArray = array(
			$author => $email
		);
		$subject = Redaxscript\Language::get('registration');
		$bodyArray = array(
			'<strong>' . Redaxscript\Language::get('name') . Redaxscript\Language::get('colon') . '</strong> ' . $name,
			'<br />',
			'<strong>' . Redaxscript\Language::get('user') . Redaxscript\Language::get('colon') . '</strong> ' . $user,
			'<br />',
			'<strong>' . Redaxscript\Language::get('password') . Redaxscript\Language::get('colon') . '</strong> ' . $password,
			'<br />',
			'<strong>' . Redaxscript\Language::get('login') . Redaxscript\Language::get('colon') . '<strong> ' . $loginLink
		);

		/* mailer object */

		$mailer = new Redaxscript\Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* create user */

		Redaxscript\Db::forTablePrefix('users')
			->create()
			->set($r)
			->save();
	}

	/* handle error */

	$messenger = new \Redaxscript\Messenger();
	if ($error)
	{
		echo $messenger->setAction(Language::get('back'), 'registration')->error($error, Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		echo $messenger->setAction(Language::get('login'), 'login')->doRedirect()->success($success, Language::get('operation_completed'));
	}
}
