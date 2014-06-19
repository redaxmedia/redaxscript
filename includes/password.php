<?php

/**
 * password reset form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Password
 * @author Henry Ruhs
 */

function password_reset_form()
{
	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$code_disabled = ' disabled="disabled"';
	}

	/* captcha object */

	$language = Redaxscript_Language::getInstance();
	$captcha = new Redaxscript_Captcha($language);

	/* collect output */

	$output = '<h2 class="title_content">' . l('password_reset') . '</h2>';
	$output .= form_element('form', 'form_reset', 'js_validate_form form_default form_reset', '', '', '', 'action="' . REWRITE_ROUTE . 'password_reset" method="post"');
	$output .= form_element('fieldset', '', 'set_reset', '', '', l('fields_request') . l('point')) . '<ul>';

	/* collect captcha task output */

	$output .= '<li>' . form_element('number', 'task', 'field_text field_note', 'task', '', $captcha->getTask(), 'min="1" max="20" required="required" autofocus="autofocus"' . $code_disabled) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect captcha solution output */

	$output .= form_element('hidden', '', '', 'solution', $captcha->getSolution());

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'id', FIRST_SUB_PARAMETER);
	$output .= form_element('hidden', '', '', 'password', THIRD_PARAMETER);
	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'js_submit button_default', 'password_reset_post', l('submit'), '', $code_disabled);
	$output .= '</form>';
	$_SESSION[ROOT . '/password_reset'] = 'visited';
	echo $output;
}

/**
 * password reset post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Password
 * @author Henry Ruhs
 */

function password_reset_post()
{
	/* clean post */

	if (ATTACK_BLOCKED < 10 && $_SESSION[ROOT . '/password_reset'] == 'visited')
	{
		$post_id = clean($_POST['id'], 0);
		$post_password = clean($_POST['password'], 0);
		$password = hash_generator(10);
		$task = $_POST['task'];
		$solution = $_POST['solution'];
	}

	/* query user information */

	if ($post_id && $post_password)
	{
		$users_query = 'SELECT id, name, email, password FROM ' . PREFIX . 'users WHERE id = ' . $post_id . ' && password = \'' . $post_password . '\' && status = 1';
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

	if ($post_id == '' || $post_password == '')
	{
		$error = l('input_incorrect');
	}
	else if (sha1($task) != $solution)
	{
		$error = l('captcha_incorrect');
	}
	else if ($my_id == '' || $my_password == '')
	{
		$error = l('access_no');
	}
	else
	{
		/* send new password */

		$loginRoute = ROOT . '/' . REWRITE_ROUTE . 'login';
		$loginLink = anchor_element('external', '', '', $loginRoute, $loginRoute);
		$toArray = array(
			$my_name => $my_email
		);
		$fromArray = array(
			s('author') => s('email')
		);
		$subject = l('password_new');
		$bodyArray = array(
			l('password_new') => $password,
			'<br />',
			l('login') => $loginLink
		);

		/* mailer object */

		$mailer = new Redaxscript_Mailer($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* update password */

		$query = 'UPDATE ' . PREFIX . 'users SET password = \'' . sha1($password) . SALT . '\' WHERE id = ' . $post_id . ' && password = \'' . $post_password . '\' && status = 1';
		mysql_query($query);
	}

	/* handle error */

	if ($error)
	{
		if (s('blocker') == 1)
		{
			$_SESSION[ROOT . '/attack_blocked']++;
		}
		if ($post_id && $post_password)
		{
			$back_route = 'password_reset/' . $post_id . '/' . $post_password;
		}
		else
		{
			$back_route = 'reminder';
		}
		notification(l('error_occurred'), $error, l('back'), $back_route);
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), l('password_sent'), l('login'), 'login');
	}
	$_SESSION[ROOT . '/password_reset'] = '';
}

/**
 * hash generator
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Password
 * @author Henry Ruhs
 *
 * @param string $length
 * @return string
 */

function hash_generator($length = '')
{
	$a = mt_rand(1, 1000000);
	$b = sha1($a);
	$output = substr($b, 0, $length);
	return $output;
}