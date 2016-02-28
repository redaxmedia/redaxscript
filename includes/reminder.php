<?php
use Redaxscript\Language;

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

				$passwordResetRoute = ROOT . '/' . REWRITE_ROUTE . 'login/reset/' . sha1($password) . '/' . $id;
				$passwordResetLink = '<a href="' . $passwordResetRoute . '">' . $passwordResetRoute . '</a>';
				$toArray = array(
					$name => $email
				);
				$fromArray = array(
					s('author') => s('email')
				);
				$subject = l('recovery');
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

	$messenger = new \Redaxscript\Messenger();
	if ($error)
	{
		echo $messenger->setAction(Language::get('back'), 'recovery')->error($error, Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		echo $messenger->setAction(Language::get('login'), 'login')->doRedirect()->success(Language::get('recovery_sent'), Language::get('operation_completed'));
	}
}
