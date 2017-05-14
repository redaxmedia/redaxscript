<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
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

	public function process()
	{
		$emailFilter = new Filter\Email();

		/* process post */

		$postArray =
		[
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

		$messageArray = [];
		$users = Db::forTablePrefix('users')->where(
		[
			'email' => $postArray['email'],
			'status' => 1
		])
		->findMany();

		/* process users */

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
					'message' => $this->_language->get('email_failed')
				]);
			}
			else
			{
				$messageArray[] = $user->name . $this->_language->get('colon') . ' ' . $this->_language->get('recovery_sent');
			}
		}
		if ($messageArray)
		{
			return $this->_success(
			[
				'message' => $messageArray
			]);
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
	 * @param array $successArray
	 *
	 * @return string
	 */

	protected function _success($successArray = [])
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
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	protected function _error($errorArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'login/recover')
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

	protected function _validate($postArray = [])
	{
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();

		/* validate post */

		$messageArray = [];
		if (!$postArray['email'])
		{
			$messageArray[] = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postArray['email']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('email_incorrect');
		}
		else if (!Db::forTablePrefix('users')->where('email', $postArray['email'])->findOne()->id)
		{
			$messageArray[] = $this->_language->get('email_unknown');
		}
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('captcha_incorrect');
		}
		return $messageArray;
	}

	/**
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray array of the mail
	 *
	 * @return boolean
	 */

	protected function _mail($mailArray = [])
	{
		$urlReset = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login/reset/' . sha1($mailArray['password']) . '/' . $mailArray['id'];

		/* html elements */

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
			Db::getSetting('author') => Db::getSetting('email')
		];
		$subject = $this->_language->get('recovery');
		$bodyArray =
		[
			$this->_language->get('user') . $this->_language->get('colon') . $mailArray['user'],
			'<br />',
			$this->_language->get('password_reset') . $this->_language->get('colon') . $linkElement
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}