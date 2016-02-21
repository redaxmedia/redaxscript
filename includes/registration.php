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
		$error = l('name_empty');
	}
	else if ($user == '')
	{
		$error = l('user_empty');
	}
	else if ($email == '')
	{
		$error = l('email_empty');
	}
	else if ($loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('user_incorrect');
	}
	else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('email_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('captcha_incorrect');
	}
	else if (Redaxscript\Db::forTablePrefix('users')->where('user', $user)->findOne()->id)
	{
		$error = l('user_exists');
	}
	else
	{
		if (USERS_NEW == 0 && s('verification') == 1)
		{
			$r['status'] = 0;
			$success = l('registration_verification');
		}
		else
		{
			$r['status'] = 1;
			$success = l('registration_sent');
		}

		/* send login information */

		$loginRoute = ROOT . '/' . REWRITE_ROUTE . 'login';
		$loginLink = anchor_element('external', '', '', $loginRoute, $loginRoute);
		$toArray = array(
			$name => $email
		);
		if (s('notification') == 1)
		{
			$toArray[s('author')] = s('email');
		}
		$fromArray = array(
			$author => $email
		);
		$subject = l('registration');
		$bodyArray = array(
			'<strong>' . l('name') . l('colon') . '</strong> ' . $name,
			'<br />',
			'<strong>' . l('user') . l('colon') . '</strong> ' . $user,
			'<br />',
			'<strong>' . l('password') . l('colon') . '</strong> ' . $password,
			'<br />',
			'<strong>' . l('login') . l('colon') . '<strong> ' . $loginLink
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
		$messenger->setAction(Language::get('back'), 'registration');
		echo $messenger->error($error, Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		$messenger->setAction(Language::get('login'), 'login');
		echo $messenger->success($success, Language::get('operation_completed'));
		echo $messenger->redirect();
	}
}
