<?php

/**
 * registration form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Registration
 * @author Henry Ruhs
 */

function registration_form()
{
	$output = Redaxscript\Hook::trigger('registrationFormStart');

	/* html elements */

	$titleElement = new Redaxscript\Html\Element();
	$titleElement->init('h2', array(
		'class' => 'rs-title-content',
	));
	$titleElement->text(Redaxscript\Language::get('account_create'));
	$formElement = new Redaxscript\Html\Form(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
	$formElement->init(array(
		'form' => array(
			'class' => 'rs-js-validate-form rs-form-default rs-form-registration'
		)
	), array(
		'captcha' => Redaxscript\Db::getSettings('captcha') > 0
	));

	/* create the form */

	$formElement
		->append('<fieldset>')
		->legend()
		->append('<ul><li>')
		->label('* ' . Redaxscript\Language::get('name'), array(
			'for' => 'name'
		))
		->text(array(
			'autofocus' => 'autofocus',
			'id' => 'name',
			'name' => 'name',
			'required' => 'required'
		))
		->append('</li><li>')
		->label('* ' . Redaxscript\Language::get('user'), array(
			'for' => 'user'
		))
		->text(array(
			'id' => 'user',
			'name' => 'user',
			'required' => 'required'
		))
		->append('</li><li>')
		->label('* ' . Redaxscript\Language::get('email'), array(
			'for' => 'email'
		))
		->email(array(
			'id' => 'email',
			'name' => 'email',
			'required' => 'required'
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
		->submit(Redaxscript\Language::get('create'), array(
			'name' => 'registration_post'
		));

	/* collect output */

	$output .= $titleElement . $formElement;
	$output .= Redaxscript\Hook::trigger('registrationFormEnd');
	echo $output;
}

/**
 * registration post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Registration
 * @author Henry Ruhs
 */

function registration_post()
{
	/* clean post */

	$name = $r['name'] = clean($_POST['name'], 0);
	$user = $r['user'] = clean($_POST['user'], 0);
	$email = $r['email'] = clean($_POST['email'], 3);
	$password = substr(sha1(uniqid()), 0, 10);
	$passwordHash = new Redaxscript\Hash(Redaxscript\Config::getInstance());
	$passwordHash->init($password);
	$r['password'] = $passwordHash->getHash();
	$r['description'] = '';
	$r['language'] = Redaxscript\Registry::get('language');
	$r['first'] = $r['last'] = NOW;
	$r['groups'] = Redaxscript\Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id;
	if ($r['groups'] == '')
	{
		$r['groups'] = 0;
	}
	$task = $_POST['task'];
	$solution = $_POST['solution'];

	/* validate post */

	$loginValidator = new Redaxscript\Validator\Login();
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();

	if ($name == '')
	{
		$error = l('name_empty');
	}
	else if ($user == '')
	{
		$error = l('user_empty');
	}
	else if ($email == '')
	{
		$error = l('email_empty');
	}
	else if ($loginValidator->validate($user) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('user_incorrect');
	}
	else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('email_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('captcha_incorrect');
	}
	else if (Redaxscript\Db::forTablePrefix('users')->where('user', $user)->findOne()->id)
	{
		$error = l('user_exists');
	}
	else
	{
		if (USERS_NEW == 0 && s('verification') == 1)
		{
			$r['status'] = 0;
			$success = l('registration_verification');
		}
		else
		{
			$r['status'] = 1;
			$success = l('registration_sent');
		}

		/* send login information */

		$loginRoute = ROOT . '/' . REWRITE_ROUTE . 'login';
		$loginLink = anchor_element('external', '', '', $loginRoute, $loginRoute);
		$toArray = array(
			$name => $email
		);
		if (s('notification') == 1)
		{
			$toArray[s('author')] = s('email');
		}
		$fromArray = array(
			$author => $email
		);
		$subject = l('registration');
		$bodyArray = array(
			'<strong>' . l('name') . l('colon') . '</strong> ' . $name,
			'<br />',
			'<strong>' . l('user') . l('colon') . '</strong> ' . $user,
			'<br />',
			'<strong>' . l('password') . l('colon') . '</strong> ' . $password,
			'<br />',
			'<strong>' . l('login') . l('colon') . '<strong> ' . $loginLink
		);

		/* mailer object */

		$mailer = new Redaxscript\Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* create user */

		Redaxscript\Db::forTablePrefix('users')
			->create()
			->set($r)
			->save();
	}

	/* handle error */

	if ($error)
	{
		notification(l('error_occurred'), $error, l('back'), 'registration');
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), $success, l('login'), 'login');
	}
}
