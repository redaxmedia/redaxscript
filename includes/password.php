<?php

/**
 * password reset form
 */

function password_reset_form()
{
	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$class_disabled = ' field_disabled';
		$code_disabled = ' disabled="disabled"';
	}

	/* new captcha object */

	$captcha = new Redaxscript_Captcha();

	/* collect output */

	$output = '<h2 class="title_content">' . l('password_reset') . '</h2>';
	$output .= form_element('form', 'form_reset', 'js_check_required form_default form_reset', '', '', '', 'action="' . REWRITE_ROUTE . 'password_reset" method="post"');
	$output .= form_element('fieldset', '', 'set_reset', '', '', l('fields_request') . l('point')) . '<ul>';

	/* collect captcha task output */

	$output .= '<li>' . form_element('number', 'task', 'js_required field_text field_note' . $class_disabled, 'task', '', $captcha->getTask(), 'maxlength="2" required="required" autofocus="autofocus"' . $code_disabled) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect captcha solution output */

	$output .= form_element('hidden', '', '', 'solution', $captcha->getSolution());
	
	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'id', FIRST_SUB_PARAMETER);
	$output .= form_element('hidden', '', '', 'password', THIRD_PARAMETER);
	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'field_button' . $class_disabled, 'password_reset_post', l('submit'), '', $code_disabled);
	$output .= '</form>';
	$_SESSION[ROOT . '/password_reset'] = 'visited';
	echo $output;
}

/**
 * password reset post
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

		$login_route = ROOT . '/' . REWRITE_ROUTE . 'login';
		$login_link = anchor_element('', '', '', $login_route, $login_route);
		$body_array = array(
			l('password_new') => $password,
			code1 => '<br />',
			l('login') => $login_link
		);
		send_mail($my_email, $my_name, s('email'), s('author'), l('password_new'), $body_array);

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
?>