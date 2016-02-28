<?php

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
	$emailFilter = new Redaxscript\Filter\Email();
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();

	/* clean post */

	$email = $emailFilter->sanitize($_POST['email']);
	$task = $_POST['task'];
	$solution = $_POST['solution'];

	/* validate post */

	if ($email == '')
	{
		$error = Redaxscript\Language::get('email_empty');
	}
	else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('email_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('captcha_incorrect');
	}
	else if (Redaxscript\Db::forTablePrefix('users')->where('email', $email)->findOne()->id == '')
	{
		$error = Redaxscript\Language::get('email_unknown');
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

				$passwordResetRoute = ROOT . '/' . REWRITE_ROUTE . 'login/reset/' . sha1($password) . '/' . $id;
				$passwordResetLink = '<a href="' . $passwordResetRoute . '">' . $passwordResetRoute . '</a>';
				$toArray = array(
					$name => $email
				);
				$fromArray = array(
					s('author') => Redaxscript\Db::getSettings('email')
				);
				$subject = Redaxscript\Language::get('recovery');
				$bodyArray = array(
					'<strong>' . Redaxscript\Language::get('user') . Redaxscript\Language::get('colon') . '</strong> ' . $user,
					'<br />',
					'<strong>' . Redaxscript\Language::get('password_reset') . Redaxscript\Language::get('colon') . '</strong> ' . $passwordResetLink
				);

				/* mailer object */

				$mailer = new Redaxscript\Mailer();
				$mailer->init($toArray, $fromArray, $subject, $bodyArray);
				$mailer->send();
			}
		}
	}

	/* handle error */

	$messenger = new Redaxscript\Messenger();
	if ($error)
	{
		echo $messenger->setAction(Redaxscript\Language::get('back'), 'recovery')->error($error, Redaxscript\Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		echo $messenger->setAction(Redaxscript\Language::get('login'), 'login')->doRedirect()->success(Redaxscript\Language::get('recovery_sent'), Redaxscript\Language::get('operation_completed'));
	}
}
