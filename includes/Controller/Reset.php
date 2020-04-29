<?php
namespace Redaxscript\Controller;

use Redaxscript\Filter;
use Redaxscript\Hash;
use Redaxscript\Html\Element;
use Redaxscript\Mailer;
use Redaxscript\Model;
use Redaxscript\Validator;
use function sha1;
use function uniqid;

/**
 * children class to process the reset request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Reset extends ControllerAbstract
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
		$passwordHash = new Hash();
		$passwordHash->init(uniqid());
		$postArray = $this->_normalizePost($this->_sanitizePost());
		$validateArray = $this->_validatePost($postArray);
		$user = $this->_getUser($postArray);

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => 'login/recover',
				'message' => $validateArray
			]);
		}

		/* handle reset */

		$resetArray =
		[
			'id' => $user->id,
			'password' => $passwordHash->getHash()
		];
		if (!$this->_reset($resetArray))
		{
			return $this->_error(
			[
				'route' => 'login/recover'
			]);
		}

		/* handle mail */

		$mailArray =
		[
			'name' => $user->name,
			'user' => $user->user,
			'email' => $user->email
		];
		if (!$this->_mail($mailArray))
		{
			return $this->_error(
			[
				'route' => 'login/recover',
				'message' => $this->_language->get('email_failed')
			]);
		}

		/* handle success */

		return $this->_success(
		[
			'route' => 'login',
			'timeout' => 2,
			'message' => $this->_language->get('password_sent')
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

		/* sanitize post */

		return
		[
			'id' => $numberFilter->sanitize($this->_request->getPost('id')),
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
		$captchaValidator = new Validator\Captcha();
		$user = $this->_getUser($postArray);
		$validateArray = [];

		/* validate post */

		if (!$postArray['id'])
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
		else if (sha1($user->password) !== $postArray['password'])
		{
			$validateArray[] = $this->_language->get('password_incorrect');
		}
		if (!$captchaValidator->validate($postArray['task'], $postArray['solution']))
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
	 * @return object|null
	 */

	protected function _getUser(array $postArray = []) : ?object
	{
		$userModel = new Model\User();
		return $userModel->getById($postArray['id']);
	}

	/**
	 * reset the password
	 *
	 * @since 3.0.0
	 *
	 * @param array $resetArray array of the reset
	 *
	 * @return bool
	 */

	protected function _reset(array $resetArray = []) : bool
	{
		$userModel = new Model\User();
		return $userModel->resetPasswordById($resetArray['id'], $resetArray['password']);
	}

	/**
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray array of the mail
	 *
	 * @return bool
	 */

	protected function _mail(array $mailArray = []) : bool
	{
		$settingModel = new Model\Setting();
		$urlLogin = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login';

		/* html element */

		$linkElement = new Element();
		$linkElement
			->init('a',
			[
				'href' => $urlLogin
			])
			->text($urlLogin);

		/* prepare mail */

		$toArray =
		[
			$mailArray['name'] => $mailArray['email']
		];
		$fromArray =
		[
			$settingModel->get('author') => $settingModel->get('email')
		];
		$subject = $this->_language->get('password_new');
		$bodyArray =
		[
			$this->_language->get('user') . $this->_language->get('colon') . ' ' . $mailArray['user'],
			'<br />',
			$this->_language->get('login') . $this->_language->get('colon') . ' ' . $linkElement
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}
