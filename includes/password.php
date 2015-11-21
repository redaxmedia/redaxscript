<?php

/**
 * password reset form
 *
 * @since 3.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Password
 * @author Henry Ruhs
 */

function password_reset_form()
{
	$output = Redaxscript\Hook::trigger('passwordFormStart');

	/* html elements */

	$titleElement = new Redaxscript\Html\Element();
	$titleElement->init('h2', array(
			'class' => 'rs-title-content',
	));
	$titleElement->text(Redaxscript\Language::get('password_reset'));
	$formElement = new Redaxscript\Html\Form(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
	$formElement->init(array(
		'form' => array(
			'class' => 'rs-js-validate-form rs-form-default rs-form-password-reset'
		),
		'button' => array(
			'submit' => array(
				'name' => 'password_reset_post'
			)
		)
	), array(
		'captcha' => true
	));

	/* create the form */

	$formElement
		->append('<fieldset>')
		->legend()
		->append('<li><ul>')
		->captcha('task')
		->append('</li></ul></fieldset>')
		->hidden(array(
			'name' => 'id',
			'value' => Redaxscript\Registry::get('firstSubParameter')
		))
		->hidden(array(
			'name' => 'password',
			'value' => Redaxscript\Registry::get('thirdParameter')
		))
		->captcha('solution')
		->token()
		->submit();

	/* collect output */

	$output .= $titleElement . $formElement;
	$output .= Redaxscript\Hook::trigger('passwordFormEnd');
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

	if ($error)
	{
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
}
