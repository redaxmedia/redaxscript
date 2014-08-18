<?php

/**
 * contact render start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_render_start()
{
	if ($_POST['contact_post'])
	{
		define('CENTER_BREAK', 1);
	}
}

/**
 * contact center start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_center_start()
{
	if ($_POST['contact_post'])
	{
		contact_post();
	}
}

/**
 * contact
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact()
{
	contact_form();
}

/**
 * contact form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_form()
{
	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$code_readonly = $code_disabled = ' disabled="disabled"';
	}

	/* define fields if logged in */

	else if (LOGGED_IN == TOKEN)
	{
		$author = MY_USER;
		$email = MY_EMAIL;
		$code_readonly = ' readonly="readonly"';
	}

	/* captcha object */

	if (s('captcha') > 0)
	{
		$captcha = new Redaxscript\Captcha(Redaxscript\Language::getInstance());
	}

	/* collect output */

	$output = form_element('form', 'form_contact', 'js_validate_form form_default form_contact', '', '', '', 'method="post"');
	$output .= form_element('fieldset', '', 'set_contact', '', '', l('fields_required') . l('point')) . '<ul>';
	$output .= '<li>' . form_element('text', 'author', 'field_text field_note', 'author', $author, '* ' . l('author'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'field_text field_note', 'email', $email, '* ' . l('email'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
	$output .= '<li>' . form_element('url', 'url', 'field_text', 'url', '', l('url'), 'maxlength="50"' . $code_disabled) . '</li>';
	$output .= '<li>' . form_element('textarea', 'text', 'js_auto_resize js_editor_textarea field_textarea field_note', 'text', '', '* ' . l('message'), 'rows="5" cols="100" required="required"' . $code_disabled) . '</li>';

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
	$output .= form_element('button', '', 'js_submit button_default', 'contact_post', l('submit'), '', $code_disabled);
	$output .= '</form>';
	$_SESSION[ROOT . '/contact'] = 'visited';
	echo $output;
}

/**
 * contact post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_post()
{
	$emailValidator = new Redaxscript_Validator_Email();
	$captchaValidator = new Redaxscript_Validator_Captcha();
	$urlValidator = new Redaxscript_Validator_Url();

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
	else if ($emailValidator->validate($email) == Redaxscript_Validator_Interface::VALIDATION_FAIL)
	{
		$error = l('email_incorrect');
	}
	else if ($url && $urlValidator->validate($url) == Redaxscript_Validator_Interface::VALIDATION_FAIL)
	{
		$error = l('url_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript_Validator_Interface::VALIDATION_FAIL)
	{
		$error = l('captcha_incorrect');
	}

	/* else send contact message */

	else
	{
		/* prepare body parts */

		$emailLink = anchor_element('email', '', '', $email, $email);
		if ($url)
		{
			$urlLink = anchor_element('external', '', '', $url, $url);
		}

		/* prepare mail inputs */

		$toArray = array(
			s('author') => s('email')
		);
		$fromArray = array(
			$author => $email
		);
		$subject = l('contact');
		$bodyArray = array(
			'<strong>' . l('author') . l('colon') . '</strong> ' . $author . ' (' . MY_IP . ')',
			'<strong>' . l('email') . l('colon') . '</strong> ' . $emailLink,
			'<strong>' . l('url') . l('colon') . '</strong> ' . $urlLink,
			'<br />',
			'<strong>' . l('message') . l('colon') . '</strong> ' . $text
		);

		/* mailer object */

		$mailer = new Redaxscript\Mailer($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();
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
		notification(l('operation_completed'), l('message_sent', 'contact'), l('home'), ROOT);
	}
	$_SESSION[ROOT . '/contact'] = '';
}
