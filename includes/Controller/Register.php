<?php
namespace Redaxscript\Controller;

use Redaxscript\Filter;
use Redaxscript\Hash;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Model;
use Redaxscript\Validator;

/**
 * children class to process the register request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Register extends ControllerAbstract
{
	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process() : string
	{
		$groupModel = new Model\Group();
		$settingModel = new Model\Setting();
		$passwordHash = new Hash();
		$postArray = $this->_normalizePost($this->_sanitizePost());
		$validateArray = $this->_validatePost($postArray);

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => 'register',
				'message' => $validateArray
			]);
		}

		/* handle create */

		$passwordHash->init($postArray['password']);
		$createArray =
		[
			'name' => $postArray['name'],
			'user' => $postArray['user'],
			'password' => $passwordHash->getHash(),
			'email' => $postArray['email'],
			'language' => $this->_registry->get('language'),
			'groups' => $groupModel->getByAlias('members')->id,
			'status' => $settingModel->get('verification') ? 0 : 1
		];
		if (!$this->_create($createArray))
		{
			return $this->_error(
			[
				'route' => 'register'
			]);
		}

		/* handle mail */

		$mailArray =
		[
			'name' => $postArray['name'],
			'user' => $postArray['user'],
			'email' => $postArray['email']
		];
		if (!$this->_mail($mailArray))
		{
			return $this->_error(
			[
				'route' => 'register',
				'message' => $this->_language->get('email_failed')
			]);
		}

		/* handle success */

		return $this->_success(
		[
			'route' => 'login',
			'timeout' => 2,
			'message' => $settingModel->get('verification') ? $this->_language->get('registration_verification') : $this->_language->get('registration_completed')
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

		/* sanitize post */

		return
		[
			'name' => $specialFilter->sanitize($this->_request->getPost('name')),
			'user' => $specialFilter->sanitize($this->_request->getPost('user')),
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
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
		$userValidator = new Validator\User();
		$passwordValidator = new Validator\Password();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();
		$settingModel = new Model\Setting();
		$userModel = new Model\User();
		$validateArray = [];

		/* validate post */

		if (!$postArray['name'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		if (!$postArray['user'])
		{
			$validateArray[] = $this->_language->get('user_empty');
		}
		else if (!$userValidator->validate($postArray['user']))
		{
			$validateArray[] = $this->_language->get('user_incorrect');
		}
		else if ($userModel->query()->where('user', $postArray['user'])->findOne()->id)
		{
			$validateArray[] = $this->_language->get('user_exists');
		}
		if (!$postArray['password'])
		{
			$validateArray[] = $this->_language->get('password_empty');
		}
		else if (!$passwordValidator->validate($postArray['password']))
		{
			$validateArray[] = $this->_language->get('password_incorrect');
		}
		if (!$postArray['email'])
		{
			$validateArray[] = $this->_language->get('email_empty');
		}
		else if (!$emailValidator->validate($postArray['email']))
		{
			$validateArray[] = $this->_language->get('email_incorrect');
		}
		if ($settingModel->get('captcha') > 0 && !$captchaValidator->validate($postArray['task'], $postArray['solution']))
		{
			$validateArray[] = $this->_language->get('captcha_incorrect');
		}
		return $validateArray;
	}

	/**
	 * create the user
	 *
	 * @since 3.0.0
	 *
	 * @param array $createArray
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$userModel = new Model\User();
		return $userModel->createByArray($createArray);
	}

	/**
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return bool
	 */

	protected function _mail(array $mailArray = []) : bool
	{
		$settingModel = new Model\Setting();
		$urlLogin = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login';

		/* html element */

		$linkElement = new Html\Element();
		$linkElement
			->init('a',
			[
				'href' => $urlLogin
			])
			->text($urlLogin);

		/* prepare mail */

		$toArray =
		[
			$mailArray['name'] => $mailArray['email'],
			$settingModel->get('author') => $settingModel->get('notification') ? $settingModel->get('email') : null
		];
		$fromArray =
		[
			$mailArray['name'] => $mailArray['email']
		];
		$subject = $this->_language->get('registration');
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
