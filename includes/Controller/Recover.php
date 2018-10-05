<?php
namespace Redaxscript\Controller;

use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Model;
use Redaxscript\Validator;

/**
 * children class to process the recover request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Recover extends ControllerAbstract
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
		$users = $this->_getUsers($postArray);

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => 'login/recover',
				'message' => $validateArray
			]);
		}

		/* handle mail and validate user */

		$validateArray = [];
		foreach ($users as $user)
		{
			$mailArray =
			[
				'id' => $user->id,
				'name' => $user->name,
				'user' => $user->user,
				'password' => $user->password,
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
			$validateArray[] = $user->name . $this->_language->get('colon') . ' ' . $this->_language->get('recovery_sent');
		}
		if ($validateArray)
		{
			return $this->_success(
			[
				'route' => 'login',
				'timeout' => 2,
				'message' => $validateArray
			]);
		}

		/* handle error */

		return $this->_error(
		[
			'route' => 'login/recover'
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
		$emailFilter = new Filter\Email();

		/* sanitize post */

		return
		[
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
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();
		$userModel = new Model\User();
		$settingModel = new Model\Setting();
		$validateArray = [];

		/* validate post */

		if (!$postArray['email'])
		{
			$validateArray[] = $this->_language->get('email_empty');
		}
		else if (!$emailValidator->validate($postArray['email']))
		{
			$validateArray[] = $this->_language->get('email_incorrect');
		}
		else if (!$userModel->query()->where('email', $postArray['email'])->findOne()->id)
		{
			$validateArray[] = $this->_language->get('email_unknown');
		}
		if ($settingModel->get('captcha') > 0 && !$captchaValidator->validate($postArray['task'], $postArray['solution']))
		{
			$validateArray[] = $this->_language->get('captcha_incorrect');
		}
		return $validateArray;
	}

	/**
	 * get the users
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return object|null
	 */

	protected function _getUsers(array $postArray = []) : ?object
	{
		$userModel = new Model\User();
		return $userModel
			->query()
			->where(
			[
				'email' => $postArray['email'],
				'status' => 1
			])
			->findMany() ? : null;
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
		$urlReset = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login/reset/' . sha1($mailArray['password']) . '/' . $mailArray['id'];

		/* html element */

		$linkElement = new Html\Element();
		$linkElement
			->init('a',
			[
				'href' => $urlReset
			])
			->text($urlReset);

		/* prepare mail */

		$toArray =
		[
			$mailArray['name'] => $mailArray['email']
		];
		$fromArray =
		[
			$settingModel->get('author') => $settingModel->get('email')
		];
		$subject = $this->_language->get('recovery');
		$bodyArray =
		[
			$this->_language->get('user') . $this->_language->get('colon') . ' ' . $mailArray['user'],
			'<br />',
			$this->_language->get('password_reset') . $this->_language->get('colon') . ' ' . $linkElement
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}
