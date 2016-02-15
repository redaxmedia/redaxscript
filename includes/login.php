<?php
use Redaxscript\Language;

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
	$passwordValidator = new Redaxscript\Validator\Password();
	$loginValidator = new Redaxscript\Validator\Login();
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();

	/* clean post */

	$post_user = $_POST['user'];
	$post_password = $_POST['password'];
	$task = $_POST['task'];
	$solution = $_POST['solution'];
	$login_by_email = 0;
	$users = Redaxscript\Db::forTablePrefix('users');
	if ($emailValidator->validate($post_user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$post_user = clean($post_user, 0);
		$users->where('user', $post_user);
	}
	else
	{
		$post_user = clean($post_user, 3);
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

	if ($post_user == '')
	{
		$error = l('user_empty');
	}
	else if ($post_password == '')
	{
		$error = l('password_empty');
	}
	else if ($login_by_email == 0 && $loginValidator->validate($post_user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('user_incorrect');
	}
	else if ($login_by_email == 1 && $emailValidator->validate($post_user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('email_incorrect');
	}
	else if ($passwordValidator->validate($post_password, $my_password) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('password_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('captcha_incorrect');
	}
	else if ($my_id == '')
	{
		$error = l('login_incorrect');
	}
	else if ($my_status == 0)
	{
		$error = l('access_no');
	}
	else
	{
		/* setup login session */

		$_SESSION[ROOT . '/logged_in'] = TOKEN;
		$_SESSION[ROOT . '/my_id'] = $my_id;
		$_SESSION[ROOT . '/my_name'] = $my_name;
		$_SESSION[ROOT . '/my_user'] = $my_user;
		$_SESSION[ROOT . '/my_email'] = $my_email;
		if (file_exists('languages/' . $my_language . '.php'))
		{
			$_SESSION[ROOT . '/language'] = $my_language;
			$_SESSION[ROOT . '/language_selected'] = 1;
		}
		$_SESSION[ROOT . '/my_groups'] = $my_groups;

		/* query groups */

		$groups_result = Redaxscript\Db::forTablePrefix('groups')->whereIdIn(explode(',', $my_groups))->where('status', 1)->findArray();
		if ($groups_result)
		{
			$num_rows = count($groups_result);
			foreach ($groups_result as $r)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$key = 'groups_' . $key;
						$$key .= stripslashes($value);
						if (++$counter < $num_rows)
						{
							$$key .= ', ';
						}
					}
				}
			}
		}

		/* setup access session */

		$access_array = array(
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users'
		);
		foreach ($access_array as $value)
		{
			$groups_value = 'groups_' . $value;
			$position_new = strpos($$groups_value, '1');
			$position_edit = strpos($$groups_value, '2');
			$position_delete = strpos($$groups_value, '3');
			$_SESSION[ROOT . '/' . $value . '_delete'] = $_SESSION[ROOT . '/' . $value . '_edit'] = $_SESSION[ROOT . '/' . $value . '_new'] = 0;
			if ($position_new > -1)
			{
				$_SESSION[ROOT . '/' . $value . '_new'] = 1;
			}
			if ($position_edit > -1)
			{
				$_SESSION[ROOT . '/' . $value . '_edit'] = 1;
			}
			if ($position_delete > -1)
			{
				$_SESSION[ROOT . '/' . $value . '_delete'] = 1;
			}
		}
		$position_modules_install = strpos($groups_modules, '1');
		$position_modules_edit = strpos($groups_modules, '2');
		$position_modules_uninstall = strpos($groups_modules, '3');
		$position_settings_edit = strpos($groups_settings, '1');
		$position_filter = strpos($groups_filter, '0');
		$_SESSION[ROOT . '/filter'] = 1;
		$_SESSION[ROOT . '/settings_edit'] = $_SESSION[ROOT . '/modules_uninstall'] = $_SESSION[ROOT . '/modules_edit'] = $_SESSION[ROOT . '/modules_install'] = 0;
		if ($position_modules_install > -1)
		{
			$_SESSION[ROOT . '/modules_install'] = 1;
		}
		if ($position_modules_edit > -1)
		{
			$_SESSION[ROOT . '/modules_edit'] = 1;
		}
		if ($position_modules_uninstall > -1)
		{
			$_SESSION[ROOT . '/modules_uninstall'] = 1;
		}
		if ($position_settings_edit > -1)
		{
			$_SESSION[ROOT . '/settings_edit'] = 1;
		}
		if ($position_filter > -1)
		{
			$_SESSION[ROOT . '/filter'] = 0;
		}
		$_SESSION[ROOT . '/update'] = NOW;
	}

	/* handle error */

	$messenger = new Redaxscript\Messenger();

	if ($error)
	{
		$messenger->setAction(Language::get('back'), 'login');
		echo $messenger->error($error, Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		$messenger->setAction(Language::get('continue'), 'admin');
		echo $messenger->success(Language::get('logged_in'), Language::get('welcome'));
		echo $messenger->redirect(null, 0);
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
	session_destroy();

	/* messenger */

	$messenger = new \Redaxscript\Messenger();
	$messenger->setAction(Language::get('continue'), 'login');
	echo $messenger->success(Language::get('logged_out'), Language::get('goodbye'));
	echo $messenger->redirect(null, 0);
}
