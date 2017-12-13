<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Hash;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
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
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();

		/* process post */

		$postArray =
		[
			'name' => $specialFilter->sanitize($this->_request->getPost('name')),
			'user' => $specialFilter->sanitize($this->_request->getPost('user')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		];

		/* handle error */

		$messageArray = $this->_validate($postArray);
		if ($messageArray)
		{
			return $this->_error(
			[
				'message' => $messageArray
			]);
		}

		/* handle success */

		$groupModel = new Model\Group();
		$settingModel = new Model\Setting();
		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init(uniqid());
		$createArray =
		[
			'name' => $postArray['name'],
			'user' => $postArray['user'],
			'password' => $passwordHash->getHash(),
			'email' => $postArray['email'],
			'language' => $this->_registry->get('language'),
			'groups' => $groupModel->getIdByAlias('members'),
			'status' => $settingModel->get('verification') ? 0 : 1
		];
		$mailArray =
		[
			'name' => $postArray['name'],
			'user' => $postArray['user'],
			'password' => $passwordHash->getRaw(),
			'email' => $postArray['email']
		];

		/* create */

		if (!$this->_create($createArray))
		{
			return $this->_error(
			[
				'message' => $this->_language->get('something_wrong')
			]);
		}

		/* mail */

		if (!$this->_mail($mailArray))
		{
			return $this->_error(
			[
				'message' => $this->_language->get('email_failed')
			]);
		}
		return $this->_success(
		[
			'message' => $settingModel->get('verification') ? $this->_language->get('registration_verification') : $this->_language->get('registration_sent')
		]);
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successArray array of the success
	 *
	 * @return string
	 */

	protected function _success(array $successArray = []) : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('login'), 'login')
			->doRedirect()
			->success($successArray['message'], $this->_language->get('operation_completed'));
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return string
	 */

	protected function _error(array $errorArray = []) : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'register')
			->error($errorArray['message'], $this->_language->get('error_occurred'));
	}

	/**
	 * validate
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validate(array $postArray = []) : array
	{
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();
		$settingModel = new Model\Setting();

		/* validate post */

		$messageArray = [];
		if (!$postArray['name'])
		{
			$messageArray[] = $this->_language->get('name_empty');
		}
		if (!$postArray['user'])
		{
			$messageArray[] = $this->_language->get('user_empty');
		}
		else if ($loginValidator->validate($postArray['user']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('user_incorrect');
		}
		else if (Db::forTablePrefix('users')->where('user', $postArray['user'])->findOne()->id)
		{
			$messageArray[] = $this->_language->get('user_exists');
		}
		if (!$postArray['email'])
		{
			$messageArray[] = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postArray['email']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('email_incorrect');
		}
		if ($settingModel->get('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('captcha_incorrect');
		}
		return $messageArray;
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

	protected function _mail($mailArray = []) : bool
	{
		$settingModel = new Model\Setting();
		$urlLogin = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login';

		/* html elements */

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
			$this->_language->get('name') . $this->_language->get('colon') . ' ' . $mailArray['name'],
			'<br />',
			$this->_language->get('user') . $this->_language->get('colon') . ' ' . $mailArray['user'],
			'<br />',
			$this->_language->get('password') . $this->_language->get('colon') . ' ' . $mailArray['password'],
			'<br />',
			$this->_language->get('login') . $this->_language->get('colon') . ' ' . $linkElement
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}