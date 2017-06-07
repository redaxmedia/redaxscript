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

class Login extends ControllerAbstract
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
		$emailValidator = new Validator\Email();
		$loginValidator = new Validator\Login();

		/* process post */

		$postArray =
		[
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		];

		/* user and email */

		$users = Db::forTablePrefix('users');
		if ($emailValidator->validate($this->_request->getPost('user')) === Validator\ValidatorInterface::PASSED)
		{
			$postArray['user'] = $emailFilter->sanitize($this->_request->getPost('user'));
			$users->where('email', $postArray['user']);
		}
		else if ($loginValidator->validate($this->_request->getPost('user')) === Validator\ValidatorInterface::PASSED)
		{
			$postArray['user'] = $specialFilter->sanitize($this->_request->getPost('user'));
			$users->where('user', $postArray['user']);
		}
		$user = $users->where('status', 1)->findOne();

		/* handle error */

		$messageArray = $this->_validate($postArray, $user);
		if ($messageArray)
		{
			return $this->_error(
			[
				'message' => $messageArray
			]);
		}

		/* handle success */

		if ($this->_login($user->id))
		{
			return $this->_success();
		}
		return $this->_error(
		[
			'message' => $this->_language->get('something_wrong')
		]);
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _success()
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), 'admin')
			->doRedirect(0)
			->success($this->_language->get('logged_in'), $this->_language->get('welcome'));
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

	protected function _error($errorArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'login')
			->error($errorArray['message'], $this->_language->get('error_occurred'));
	}

	/**
	 * validate
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array of the post
	 * @param object $user object of the user
	 *
	 * @return array
	 */

	protected function _validate($postArray = [], $user = null)
	{
		$passwordValidator = new Validator\Password();
		$captchaValidator = new Validator\Captcha();

		/* validate post */

		$messageArray = [];
		if (!$postArray['user'])
		{
			$messageArray[] = $this->_language->get('user_empty');
		}
		else if (!$user->id)
		{
			$messageArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['password'])
		{
			$messageArray[] = $this->_language->get('password_empty');
		}
		else if ($user->password && $passwordValidator->validate($postArray['password'], $user->password) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('password_incorrect');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('captcha_incorrect');
		}
		return $messageArray;
	}

	/**
	 * login the user
	 *
	 * @param integer $userId identifier of the user
	 *
	 * @since 3.0.0
	 *
	 * @return integer
	 */

	protected function _login($userId = null)
	{
		$auth = new Auth($this->_request);
		return $auth->login($userId);
	}
}