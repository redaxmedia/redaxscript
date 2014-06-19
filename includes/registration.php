<?php

/**
 * registration form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Registration
 * @author Henry Ruhs
 */

function registration_form()
{
	hook(__FUNCTION__ . '_start');

	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$code_disabled = ' disabled="disabled"';
	}

	/* captcha object */

	if (s('captcha') > 0)
	{
		$language = Redaxscript_Language::getInstance();
		$captcha = new Redaxscript_Captcha($language);
	}

	/* collect output */

	$output = '<h2 class="title_content">' . l('account_create') . '</h2>';
	$output .= form_element('form', 'form_registration', 'js_validate_form form_default form_registration', '', '', '', 'action="' . REWRITE_ROUTE . 'registration" method="post"');
	$output .= form_element('fieldset', '', 'set_registration', '', '', l('fields_required') . l('point')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'field_text field_note', 'name', '', '* ' . l('name'), 'maxlength="50" required="required" autofocus="autofocus"' . $code_disabled) . '</li>';
	$output .= '<li>' . form_element('text', 'user', 'field_text field_note', 'user', '', '* ' . l('user'), 'maxlength="50" required="required"' . $code_disabled) . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'field_text field_note', 'email', '', '* ' . l('email'), 'maxlength="50" required="required"' . $code_disabled) . '</li>';

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
	$output .= form_element('button', '', 'js_submit button_default', 'registration_post', l('create'), '', $code_disabled);
	$output .= '</form>';
	$_SESSION[ROOT . '/registration'] = 'visited';
	echo $output;
	hook(__FUNCTION__ . '_end');
}

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

	if (ATTACK_BLOCKED < 10 && $_SESSION[ROOT . '/registration'] == 'visited')
	{
		$name = $r['name'] = clean($_POST['name'], 0);
		$user = $r['user'] = clean($_POST['user'], 0);
		$email = $r['email'] = clean($_POST['email'], 3);
		$password = hash_generator(10);
		$r['password'] = sha1($password) . SALT;
		$r['description'] = '';
		$r['language'] = LANGUAGE;
		$r['first'] = $r['last'] = NOW;
		$r['groups'] = retrieve('id', 'groups', 'alias', 'members');
		if ($r['groups'] == '')
		{
			$r['groups'] = 0;
		}
		$task = $_POST['task'];
		$solution = $_POST['solution'];
	}

	/* validate post */

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
	else if (check_login($user) == 0)
	{
		$error = l('user_incorrect');
	}
	else if (check_email($email) == 0)
	{
		$error = l('email_incorrect');
	}
	else if (check_captcha($task, $solution) == 0)
	{
		$error = l('captcha_incorrect');
	}
	else if (retrieve('id', 'users', 'user', $user))
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
			l('name') => $name . ' (' . MY_IP . ')',
			l('user') => $user,
			l('password') => $password,
			'<br />',
			l('login') => $loginLink
		);

		/* mailer object */

		$mailer = new Redaxscript_Mailer($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* build key and value strings */

		$r_keys = array_keys($r);
		$last = end($r_keys);
		foreach ($r as $key => $value)
		{
			$key_string .= $key;
			$value_string .= '\'' . $value . '\'';
			if ($last != $key)
			{
				$key_string .= ', ';
				$value_string .= ', ';
			}
		}

		/* insert user */

		$query = 'INSERT INTO ' . PREFIX . 'users (' . $key_string . ') VALUES (' . $value_string . ')';
		mysql_query($query);
	}

	/* handle error */

	if ($error)
	{
		if (s('blocker') == 1)
		{
			$_SESSION[ROOT . '/attack_blocked']++;
		}
		notification(l('error_occurred'), $error, l('back'), 'registration');
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), $success, l('login'), 'login');
	}
	$_SESSION[ROOT . '/registration'] = '';
}