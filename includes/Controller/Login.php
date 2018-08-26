<?php
namespace Redaxscript\Controller;

use Redaxscript\Auth;
use Redaxscript\Filter;
use Redaxscript\Model;
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

	public function process() : string
	{
		$postArray = $this->_normalizePost($this->_sanitizePost());
		$validateArray = $this->_validatePost($postArray);
		$user = $this->_getUser($postArray);

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => 'login',
				'message' => $validateArray
			]);
		}

		/* handle login */

		if ($this->_login($user->id))
		{
			return $this->_success(
			[
				'route' => 'admin',
				'timeout' => 0,
				'message' => $this->_language->get('logged_in'),
				'title' => $this->_language->get('welcome')
			]);
		}

		/* handle error */

		return $this->_error(
		[
			'route' => 'login'
		]);
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$numberFilter = new Filter\Number();
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();
		$emailValidator = new Validator\Email();
		$loginValidator = new Validator\Login();

		/* sanitize post */

		return
		[
			'email' => $emailValidator->validate($this->_request->getPost('user')) ? $emailFilter->sanitize($this->_request->getPost('user')) : null,
			'user' => $loginValidator->validate($this->_request->getPost('user')) ? $specialFilter->sanitize($this->_request->getPost('user')) : null,
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $numberFilter->sanitize($this->_request->getPost('task')),
			'solution' => $this->_request->getPost('solution')
		];
	}

	/**
	 * validate the post
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validatePost(array $postArray = []) : array
	{
		$passwordValidator = new Validator\Password();
		$captchaValidator = new Validator\Captcha();
		$settingModel = new Model\Setting();
		$user = $this->_getUser($postArray);
		$validateArray = [];

		/* validate post */

		if (!$postArray['user'] && !$postArray['email'])
		{
			$validateArray[] = $this->_language->get('user_empty');
		}
		else if (!$user->id)
		{
			$validateArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['password'])
		{
			$validateArray[] = $this->_language->get('password_empty');
		}
		else if ($user->password && !$passwordValidator->validate($postArray['password'], $user->password))
		{
			$validateArray[] = $this->_language->get('password_incorrect');
		}
		if ($settingModel->get('captcha') > 0 && !$captchaValidator->validate($postArray['task'], $postArray['solution']))
		{
			$validateArray[] = $this->_language->get('captcha_incorrect');
		}
		return $validateArray;
	}

	/**
	 * get the user
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return object
	 */

	protected function _getUser(array $postArray = [])
	{
		$userModel = new Model\User();
		return $userModel->getByUserOrEmail($postArray['user'], $postArray['email']);
	}

	/**
	 * login the user
	 *
	 * @since 3.0.0
	 *
	 * @param int $userId identifier of the user
	 *
	 * @return int
	 */

	protected function _login(int $userId = null) : int
	{
		$auth = new Auth($this->_request);
		return $auth->login($userId);
	}
}
