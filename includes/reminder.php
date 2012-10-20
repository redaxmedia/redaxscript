<?php

/* reminder form */

function reminder_form()
{
	hook(__FUNCTION__ . '_start');

	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$class_disabled = ' field_disabled';
		$code_disabled = ' disabled="disabled"';
	}

	/* collect output */

	$output = '<h2 class="title_content">' . l('reminder') . '</h2>';
	$output .= form_element('form', 'form_reminder', 'js_check_required form_default form_reminder', '', '', '', 'action="' . REWRITE_STRING . 'reminder" method="post"');
	$output .= form_element('fieldset', '', 'set_reminder', '', '', l('reminder_request') . l('point')) . '<ul>';
	$output .= '<li>' . form_element('email', 'email', 'js_required field_text field_note' . $class_disabled, 'email', '', l('email'), 'maxlength="50" required="required" autofocus="autofocus"' . $code_disabled) . '</li>';

	/* collect captcha task output */

	if (LOGGED_IN != TOKEN && s('captcha') > 0)
	{
		$output .= '<li>' . form_element('number', 'task', 'js_required field_text field_note' . $class_disabled, 'task', '', captcha('task'), 'maxlength="2" required="required"' . $code_disabled) . '</li>';
	}
	$output .= '</ul></fieldset>';

	/* collect captcha solution output */

	if (s('captcha') > 0)
	{
		$output .= form_element('hidden', '', '', 'solution', captcha('solution'));
	}

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'field_button' . $class_disabled, 'reminder_post', l('submit'), '', $code_disabled);
	$output .= '</form>';
	$_SESSION[ROOT . '/reminder'] = 'visited';
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* reminder post */

function reminder_post()
{
	/* clean post */

	if (ATTACK_BLOCKED < 10 && $_SESSION[ROOT . '/reminder'] == 'visited')
	{
		$email = clean($_POST['email'], 3);
		$task = $_POST['task'];
		$solution = $_POST['solution'];
	}

	/* validate post */

	if ($email == '')
	{
		$error = l('email_empty');
	}
	else if (check_email($email) == 0)
	{
		$error = l('email_incorrect');
	}
	else if (check_captcha($task, $solution) == 0)
	{
		$error = l('captcha_incorrect');
	}
	else if (retrieve('id', 'users', 'email', $email) == '')
	{
		$error = l('email_unknown');
	}
	else
	{
		/* query users */

		$query = 'SELECT id, user, password FROM ' . PREFIX . 'users WHERE email = \'' . $email . '\' && status = 1';
		$result = mysql_query($query);
		if ($result)
		{
			while ($r = mysql_fetch_assoc($result))
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}

				/* send reminder information */

				$password_reset_string = ROOT . '/' . REWRITE_STRING . 'password_reset/' . $id . '/' . $password;
				$password_reset_link = anchor_element('', '', '', $password_reset_string, $password_reset_string);
				$body_array = array(
					l('user') => $user,
					code1 => '<br />',
					l('password_reset') => $password_reset_link
				);
				send_mail($email, $name, s('email'), s('author'), l('reminder'), $body_array);
			}
		}
	}

	/* handle error */

	if ($error)
	{
		if (s('blocker') == 1)
		{
			$_SESSION[ROOT . '/attack_blocked']++;
		}
		notification(l('error_occurred'), $error, l('back'), 'reminder');
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), l('reminder_sent'), l('login'), 'login');
	}
	$_SESSION[ROOT . '/reminder'] = '';
}
?>