<?php
namespace Redaxscript\Controller;

use Redaxscript\Auth;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Messenger;
use Redaxscript\Validator;

/**
 * children class to process the login request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Login extends ControllerAbstract implements ControllerInterface
{
	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$passwordValidator = new Validator\Password();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();
		$auth = new Auth($this->_request);

		/* process post */

		$postArray = array(
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* user and email */

		$users = Db::forTablePrefix('users');
		if ($emailValidator->validate($this->_request->getPost('user')) === Validator\ValidatorInterface::PASSED)
		{
			$postArray['user'] = $emailFilter->sanitize($this->_request->getPost('user'));
			$users->where('email', $postArray['user']);
		}
		else
		{
			$postArray['user'] = $specialFilter->sanitize($this->_request->getPost('user'));
			$users->where('user', $postArray['user']);
		}
		$user = $users->where('status', 1)->findOne();

		/* validate post */

		if (!$postArray['user'])
		{
			$errorArray[] = $this->_language->get('user_empty');
		}
		else if (!$user->id)
		{
			$errorArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['password'])
		{
			$errorArray[] = $this->_language->get('password_empty');
		}
		else if ($user->password && $passwordValidator->validate($postArray['password'], $user->password) === Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('password_incorrect');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('captcha_incorrect');
		}

		/* handle error */

		if ($errorArray)
		{
			return $this->error($errorArray);
		}

		/* handle success */

		else if ($auth->login($user->id))
		{
			return $this->success();
		}
		return $this->error($this->_language->get('something_wrong'));
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function success()
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('continue'), 'admin')->doRedirect(0)->success($this->_language->get('logged_in'), $this->_language->get('welcome'));
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('back'), 'login')->error($errorArray, $this->_language->get('error_occurred'));
	}
}