<?php

/**
 * contact center start
 */

function contact_center_start()
{
	if ($_POST['contact_post'])
	{
		define('CENTER_BREAK', 1);
		contact_post();
	}
}

/**
 * contact
 */

function contact()
{
	contact_form();
}

/**
 * contact form
 */

function contact_form()
{
	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$class_readonly = $class_disabled = ' field_disabled';
		$code_readonly = $code_disabled = ' disabled="disabled"';
	}

	/* define fields if logged in */

	else if (LOGGED_IN == TOKEN)
	{
		$author = MY_USER;
		$email = MY_EMAIL;
		$class_readonly = ' field_readonly';
		$code_readonly = ' readonly="readonly"';
	}

	/* collect output */

	$output = form_element('form', 'form_contact', 'js_check_required form_default form_contact', '', '', '', 'method="post"');
	$output .= form_element('fieldset', '', 'set_contact', '', '', l('fields_required') . l('point')) . '<ul>';
	$output .= '<li>' . form_element('text', 'author', 'js_required field_text field_note' . $class_readonly, 'author', $author, '* ' . l('author'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'js_required field_text field_note' . $class_readonly, 'email', $email, '* ' . l('email'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
	$output .= '<li>' . form_element('url', 'url', 'field_text' . $class_disabled, 'url', '', l('url'), 'maxlength="50"' . $code_disabled) . '</li>';
	$output .= '<li>' . form_element('textarea', 'text', 'js_required js_auto_resize js_editor field_textarea field_note' . $class_disabled, 'text', '', '* ' . l('message'), 'rows="5" cols="100" required="required"' . $code_disabled) . '</li>';

	/* collect captcha task output */

	if (LOGGED_IN != TOKEN && s('captcha') > 0)
	{
		$output .= '<li>' . form_element('number', 'task', 'js_required field_text field_note' . $class_disabled, 'task', '', captcha('task'), 'maxlength="2" required="required"' . $code_disabled);
	}
	$output .= '</ul></fieldset>';

	/* collect captcha solution output */

	if (LOGGED_IN != TOKEN && s('captcha') > 0)
	{
		$output .= form_element('hidden', '', '', 'solution', captcha('solution'));
	}

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'field_button' . $class_disabled, 'contact_post', l('submit'), '', $code_disabled);
	$output .= '</form>';
	$_SESSION[ROOT . '/contact'] = 'visited';
	echo $output;
}

/**
 * contact post
 */

function contact_post()
{
	/* clean post */

	if (ATTACK_BLOCKED < 10 && $_SESSION[ROOT . '/contact'] == 'visited')
	{
		$author = clean($_POST['author'], 0);
		$email = clean($_POST['email'], 3);
		$url = clean($_POST['url'], 4);
		$text = break_up($_POST['text']);
		$text = clean($text, 1);
		$task = $_POST['task'];
		$solution = $_POST['solution'];
	}

	/* validate post */

	if ($author == '')
	{
		$error = l('author_empty');
	}
	else if ($email == '')
	{
		$error = l('email_empty');
	}
	else if ($text == '')
	{
		$error = l('message_empty');
	}
	else if (check_email($email) == 0)
	{
		$error = l('email_incorrect');
	}
	else if ($url && check_url($url) == 0)
	{
		$error = l('url_incorrect');
	}
	else if (check_captcha($task, $solution) == 0)
	{
		$error = l('captcha_incorrect');
	}
	else
	{
		/* send contact message */

		$email_link = anchor_element('email', '', '', $email, $email);
		if ($url)
		{
			$url_link = anchor_element('external', '', '', $url, $url);
		}
		$body_array = array(
			l('author') => $author . ' (' . MY_IP . ')',
			l('email') => $email_link,
			l('url') => $url_link,
			code1 => '<br />',
			l('message') => $text
		);
		send_mail(s('email'), s('author'), $email, $author, l('contact'), $body_array);
	}

	/* handle error */

	if ($error)
	{
		if (s('blocker') == 1)
		{
			$_SESSION[ROOT . '/attack_blocked']++;
		}
		notification(l('error_occurred'), $error, l('home'), ROOT);
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), l('contact_message_sent'), l('home'), ROOT);
	}
	$_SESSION[ROOT . '/contact'] = '';
}
?>