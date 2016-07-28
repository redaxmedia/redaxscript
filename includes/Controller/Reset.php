<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Hash;
use Redaxscript\Html\Element;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Filter;
use Redaxscript\Validator;

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

	public function process()
	{
		$specialFilter = new Filter\Special();

		/* process post */

		$postArray =
		[
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		];

		/* query user */

		$user = Db::forTablePrefix('users')->where(
		[
			'id' => $postArray['id'],
			'status' => 1
		])->findOne();

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

		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init(uniqid());
		$resetArray =
		[
			'id' => $user->id,
			'password' => $passwordHash->getHash()
		];
		$mailArray =
		[
			'name' => $user->name,
			'email' => $user->email,
			'password' => $passwordHash->getRaw()
		];

		/* reset and mail */

		if ($this->_reset($resetArray) && $this->_mail($mailArray))
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
			->setRoute($this->_language->get('login'), 'login')
			->doRedirect()
			->success($this->_language->get('password_sent'), $this->_language->get('operation_completed'));
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
	 * @param object $user object of the user
	 *
	 * @return array
	 */

	protected function _validate($postArray = [], $user = null)
	{
		$captchaValidator = new Validator\Captcha();

		/* validate post */

		$messageArray = [];
		if (!$postArray['id'])
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
		else if (sha1($user->password) !== $postArray['password'])
		{
			$messageArray[] = $this->_language->get('password_incorrect');
		}
		if ($captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('captcha_incorrect');
		}
		return $messageArray;
	}

	/**
	 * reset the password
	 *
	 * @since 3.0.0
	 *
	 * @param array $resetArray array of the reset
	 *
	 * @return boolean
	 */

	protected function _reset($resetArray = [])
	{
		return Db::forTablePrefix('users')
			->where(
			[
				'id' => $resetArray['id'],
				'status' => 1
			])
			->findOne()
			->set('password', $resetArray['password'])
			->save();
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
		$urlReset = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login';

		/* html elements */

		$linkElement = new Element();
		$linkElement
			->init('a',
			[
				'href' => $urlReset,
				'class' => 'link-result'
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
		$subject = $this->_language->get('password_new');
		$bodyArray =
		[
			'<strong>' . $this->_language->get('password_new') . $this->_language->get('colon') . '</strong> ' . $mailArray['password'],
			'<br />',
			'<strong>' . $this->_language->get('login') . $this->_language->get('colon') . '</strong> ' . $linkElement
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}