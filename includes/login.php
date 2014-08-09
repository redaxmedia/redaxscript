<?php

/**
 * login form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Login
 * @author Henry Ruhs
 */

function login_form()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$code_disabled = ' disabled="disabled"';
	}

	/* captcha object */

	if (s('captcha') > 0)
	{
		$captcha = new Redaxscript\Captcha(Redaxscript\Language::getInstance());
	}

	/* reminder question */

	if (s('reminder') == 1)
	{
		$legend = anchor_element('internal', '', 'link_legend', l('reminder_question') . l('question_mark'), 'reminder', '', 'rel="nofollow"');
	}
	else
	{
		$legend = l('fields_limited') . l('point');
	}

	/* collect output */

	$output .= '<h2 class="title_content">' . l('login') . '</h2>';
	$output .= form_element('form', 'form_login', 'js_validate_form form_default form_login', '', '', '', 'action="' . REWRITE_ROUTE . 'login" method="post"');
	$output .= form_element('fieldset', '', 'set_login', '', '', $legend) . '<ul>';
	$output .= '<li>' . form_element('text', 'user', 'field_text field_note', 'user', '', l('user'), 'maxlength="50" required="required" autofocus="autofocus"' . $code_disabled) . '</li>';
	$output .= '<li>' . form_element('password', 'password', 'js_unmask_password field_text field_note', 'password', '', l('password'), 'maxlength="50" required="required" autocomplete="off"' . $code_disabled) . '</li>';

	/* collect captcha task output */

	if (LOGGED_IN != TOKEN && s('captcha') > 0)
	{
		$output .= '<li>' . form_element('number', 'task', 'field_text field_note', 'task', '', $captcha->getTask(), 'min="1" max="20" required="required"' . $code_disabled) . '</li>';
	}
	$output .= '</ul></fieldset>';

	/* collect captcha solution output */

	if (s('captcha') > 0)
	{
		if (LOGGED_IN == TOKEN)
		{
			$output .= form_element('hidden', '', '', 'task', $captcha->getSolution('raw'));
		}
		$output .= form_element('hidden', '', '', 'solution', $captcha->getSolution());
	}

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'js_submit button_default', 'login_post', l('submit'), '', $code_disabled);
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	$_SESSION[ROOT . '/login'] = 'visited';
	echo $output;
}

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
	$loginValidator = new Redaxscript_Validator_Login();
	$emailValidator = new Redaxscript_Validator_Email();
	$captchaValidator = new Redaxscript_Validator_Captcha();

	/* clean post */

	if (ATTACK_BLOCKED < 10 && $_SESSION[ROOT . '/login'] == 'visited')
	{
		$post_user = $_POST['user'];
		$post_password = $_POST['password'];
		$task = $_POST['task'];
		$solution = $_POST['solution'];
		$login_by_email = 0;
		$users_query = 'SELECT id, name, user, email, password, language, status, groups FROM ' . PREFIX . 'users ';

		if ($emailValidator->validate($post_user) == Redaxscript_Validator_Interface::VALIDATION_FAIL)
		{
			$post_user = clean($post_user, 0);
			$users_query .= 'WHERE user = \'' . $post_user . '\' LIMIT 1';
		}
		else
		{
			$post_user = clean($post_user, 3);
			$login_by_email = 1;
			$users_query .= 'WHERE email = \'' . $post_user . '\' LIMIT 1';
		}
		$users_result = mysql_query($users_query);
		while ($r = mysql_fetch_assoc($users_result))
		{
			foreach ($r as $key => $value)
			{
				$key = 'my_' . $key;
				$$key = stripslashes($value);
			}
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
	else if ($login_by_email == 0 && $loginValidator->validate($post_user) == Redaxscript_Validator_Interface::VALIDATION_FAIL)
	{
		$error = l('user_incorrect');
	}
	else if ($login_by_email == 1 && $emailValidator->validate($post_user) == Redaxscript_Validator_Interface::VALIDATION_FAIL)
	{
		$error = l('email_incorrect');
	}
	else if ($loginValidator->validate($post_password) == Redaxscript_Validator_Interface::VALIDATION_FAIL
	)
	{
		$error = l('password_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript_Validator_Interface::VALIDATION_FAIL)
	{
		$error = l('captcha_incorrect');
	}
	else if ($my_id == '' || (md5($post_password) . SALT != $my_password && sha1($post_password) . SALT != $my_password))
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

		$groups_query = 'SELECT categories, articles, extras, comments, groups, users, modules, settings, filter FROM ' . PREFIX . 'groups WHERE id IN (' . $my_groups . ') && status = 1';
		$groups_result = mysql_query($groups_query);
		if ($groups_result)
		{
			$num_rows = mysql_num_rows($groups_result);
			while ($r = mysql_fetch_assoc($groups_result))
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

	if ($error)
	{
		if (s('blocker') == 1)
		{
			$_SESSION[ROOT . '/attack_blocked']++;
		}
		notification(l('error_occurred'), $error, l('back'), 'login');
	}

	/* handle success */

	else
	{
		notification(l('welcome'), l('logged_in'), l('continue'), 'admin');
	}
	$_SESSION[ROOT . '/login'] = '';
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
	notification(l('goodbye'), l('logged_out'), l('continue'), 'login');
}