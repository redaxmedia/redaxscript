<?php
use Redaxscript\Language;

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
	$captchaValidator = new Redaxscript\Validator\Captcha();

	/* clean post */

	$post_id = clean($_POST['id'], 0);
	$post_password = clean($_POST['password'], 0);
	$password = substr(sha1(uniqid()), 0, 10);
	$task = $_POST['task'];
	$solution = $_POST['solution'];

	/* query user information */

	if ($post_id && $post_password)
	{
		$users_result = Redaxscript\Db::forTablePrefix('users')->where(array(
			'id' => $post_id,
			'status' => 1
		))->findArray();
		foreach ($users_result as $r)
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
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('captcha_incorrect');
	}
	else if ($my_id == '' || sha1($my_password) != $post_password)
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
			'<strong>' . l('password_new') . l('colon') . '</strong> ' . $password,
			'<br />',
			'<strong>' . l('login') . l('colon') . '</strong> ' . $loginLink
		);

		/* mailer object */

		$mailer = new Redaxscript\Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* update password */

		$passwordHash = new Redaxscript\Hash(Redaxscript\Config::getInstance());
		$passwordHash->init($password);
		Redaxscript\Db::forTablePrefix('users')
			->where(array(
				'id' => $post_id,
				'status' => 1
			))
			->findOne()
			->set('password', $passwordHash->getHash())
			->save();
	}

	/* handle error */

	$messenger = new \Redaxscript\Messenger();

	if ($error)
	{
		if ($post_id && $post_password)
		{
			$back_route = 'login/reset/' . $post_password . '/' . $post_id;
		}
		else
		{
			$back_route = 'login/recover';
		}

		/* show error */

		$messenger->setAction(Language::get('back'), $back_route);
		echo $messenger->error($error, Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		$messenger->setAction(Language::get('login'), 'login');
		echo $messenger->success(Language::get('password_sent'), Language::get('operation_completed'));
		echo $messenger->redirect();
	}
}
