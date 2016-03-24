<?php

/**
 * login post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Login
 * @author Henry Ruhs
 */

function login_post()
{
	$specialFilter = new Redaxscript\Filter\Special();
	$emailFilter = new Redaxscript\Filter\Email();
	$passwordValidator = new Redaxscript\Validator\Password();
	$loginValidator = new Redaxscript\Validator\Login();
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();

	/* clean post */

	$post_user = $_POST['user'];
	$post_password = $_POST['password'];
	$task = $_POST['task'];
	$solution = $_POST['solution'];
	$users = Redaxscript\Db::forTablePrefix('users');
	if ($emailValidator->validate($post_user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$post_user = $specialFilter->sanitize($post_user);
		$login_by_email = 0;
		$users->where('user', $post_user);
	}
	else
	{
		$post_user = $emailFilter->sanitize($post_user);
		$login_by_email = 1;
		$users->where('email', $post_user);
	}
	$users_result = $users->findArray();
	foreach ($users_result as $r)
	{
		foreach ($r as $key => $value)
		{
			$key = 'my_' . $key;
			$$key = stripslashes($value);
		}
	}

	/* validate post */

	if (!$post_user)
	{
		$error = Redaxscript\Language::get('user_empty');
	}
	else if (!$post_password)
	{
		$error = Redaxscript\Language::get('password_empty');
	}
	else if ($login_by_email == 0 && $loginValidator->validate($post_user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('user_incorrect');
	}
	else if ($login_by_email == 1 && $emailValidator->validate($post_user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('email_incorrect');
	}
	else if ($passwordValidator->validate($post_password, $my_password) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('password_incorrect');
	}
	else if (Redaxscript\Db::getSetting('captcha') > 0 && $captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('captcha_incorrect');
	}
	else if (!$my_id)
	{
		$error = Redaxscript\Language::get('login_incorrect');
	}
	else if ($my_status == 0)
	{
		$error = Redaxscript\Language::get('access_no');
	}
	else
	{
		$auth = new Redaxscript\Auth(Redaxscript\Request::getInstance());
		$auth->login($my_id);
		if (file_exists('languages/' . $my_language . '.php'))
		{
			Redaxscript\Request::setSession('language', $my_language);
		}
		Redaxscript\Request::setSession('update', Redaxscript\Registry::get('now'));
	}

	/* handle error */

	$messenger = new Redaxscript\Messenger();
	if ($error)
	{
		echo $messenger->setAction(Redaxscript\Language::get('back'), 'login')->error($error, Redaxscript\Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		echo $messenger->setAction(Redaxscript\Language::get('continue'), 'admin')->doRedirect(0)->success(Redaxscript\Language::get('logged_in'), Redaxscript\Language::get('welcome'));
	}
}

/**
 * logout
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Login
 * @author Henry Ruhs
 */

function logout()
{
	$auth = new Redaxscript\Auth(Redaxscript\Request::getInstance());
	$auth->logout();

	/* show success */

	$messenger = new Redaxscript\Messenger();
	echo $messenger->setAction(Redaxscript\Language::get('continue'), 'login')->doRedirect(0)->success(Redaxscript\Language::get('logged_out'), Redaxscript\Language::get('goodbye'));
}
