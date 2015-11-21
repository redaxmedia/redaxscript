<?php

/**
 * reminder form
 *
 * @since 3.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Reminder
 * @author Henry Ruhs
 */

function reminder_form()
{
	$output = Redaxscript\Hook::trigger('reminderFormStart');

	/* html elements */

	$titleElement = new Redaxscript\Html\Element();
	$titleElement->init('h2', array(
		'class' => 'rs-title-content',
	));
	$titleElement->text(Redaxscript\Language::get('reminder'));
	$formElement = new Redaxscript\Html\Form(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
	$formElement->init(array(
		'form' => array(
			'class' => 'rs-js-validate-form rs-form-default rs-form-reminder'
		),
		'button' => array(
			'submit' => array(
					'name' => 'reminder_post'
			)
		)
	), array(
		'captcha' => Redaxscript\Db::getSettings('captcha') > 0
	));

	/* create the form */

	$formElement
		->append('<fieldset>')
		->legend(Redaxscript\Language::get('reminder_request'))
		->append('<ul><li>')
		->label('* ' . Redaxscript\Language::get('email'), array(
			'for' => 'email'
		))
		->email(array(
			'autofocus' => 'autofocus',
			'id' => 'email',
			'name' => 'email',
			'required' => 'required',
			'value' => Redaxscript\Request::getPost('email')
		))
		->append('</li>');
	if (Redaxscript\Db::getSettings('captcha') > 0)
	{
		$formElement
			->append('<li>')
			->captcha('task')
			->append('</li>');
	}
	$formElement->append('</ul></fieldset>');
	if (Redaxscript\Db::getSettings('captcha') > 0)
	{
		$formElement->captcha('solution');
	}
	$formElement
		->token()
		->submit();

	/* collect output */

	$output .= $titleElement . $formElement;
	$output .= Redaxscript\Hook::trigger('reminderFormEnd');
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

	$email = clean($_POST['email'], 3);
	$task = $_POST['task'];
	$solution = $_POST['solution'];

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
		notification(l('error_occurred'), $error, l('back'), 'reminder');
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), l('reminder_sent'), l('login'), 'login');
	}
}
