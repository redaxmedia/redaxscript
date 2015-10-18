<?php

/**
 * reminder form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Reminder
 * @author Henry Ruhs
 */

function reminder_form()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$code_disabled = ' disabled="disabled"';
	}

	/* captcha object */

	$captcha = new Redaxscript\Captcha(Redaxscript\Language::getInstance());
	$captcha->init();

	/* collect output */

	$output .= '<h2 class="rs-title-content">' . l('reminder') . '</h2>';
	$output .= form_element('form', 'form_reminder', 'rs-js-validate-form rs-form-default rs-form-reminder', '', '', '', 'action="' . REWRITE_ROUTE . 'reminder" method="post"');
	$output .= form_element('fieldset', '', 'rs-set-reminder', '', '', l('reminder_request') . l('point')) . '<ul>';
	$output .= '<li>' . form_element('email', 'email', 'rs-field-text rs-field-note', 'email', '', l('email'), 'maxlength="50" required="required" autofocus="autofocus"' . $code_disabled) . '</li>';

	/* collect captcha task output */

	$output .= '<li>' . form_element('number', 'task', 'rs-field-text rs-field-note', 'task', '', $captcha->getTask(), 'min="1" max="20" required="required"' . $code_disabled) . '</li>';
	$output .= '</ul></fieldset>';

	/* collect captcha solution output */

	$captchaHash = new Redaxscript\Hash(Redaxscript\Config::getInstance());
	$captchaHash->init($captcha->getSolution());
	$output .= form_element('hidden', '', '', 'solution', $captchaHash->getHash());

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'rs-js-submit rs-button-default', 'reminder_post', l('submit'), '', $code_disabled);
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	$_SESSION[ROOT . '/reminder'] = 'visited';
	echo $output;
}

/**
 * reminder post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Reminder
 * @author Henry Ruhs
 */

function reminder_post()
{
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();

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
	else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('email_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('captcha_incorrect');
	}
	else if (Redaxscript\Db::forTablePrefix('users')->where('email', $email)->findOne()->id == '')
	{
		$error = l('email_unknown');
	}
	else
	{
		/* query users */

		$result = Redaxscript\Db::forTablePrefix('users')->where(array(
			'email' => $email,
			'status' => 1
		))->findArray();
		if ($result)
		{
			foreach ($result as $r)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}

				/* send reminder information */

				$passwordResetRoute = ROOT . '/' . REWRITE_ROUTE . 'password_reset/' . $id . '/' . sha1($password);
				$passwordResetLink = anchor_element('external', '', '', $passwordResetRoute, $passwordResetRoute);
				$toArray = array(
					s('author') => s('email')
				);
				$fromArray = array(
					$name => $email
				);
				$subject = l('reminder');
				$bodyArray = array(
					'<strong>' . l('user') . l('colon') . '</strong> ' . $user,
					'<br />',
					'<strong>' . l('password_reset') . l('colon') . '</strong> ' . $passwordResetLink
				);

				/* mailer object */

				$mailer = new Redaxscript\Mailer();
				$mailer->init($toArray, $fromArray, $subject, $bodyArray);
				$mailer->send();
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
