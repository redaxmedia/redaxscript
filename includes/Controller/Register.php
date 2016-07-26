<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Hash;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
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

	public function process()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();

		/* process post */

		$postArray = array(
			'name' => $specialFilter->sanitize($this->_request->getPost('name')),
			'user' => $specialFilter->sanitize($this->_request->getPost('user')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* handle error */

		$messageArray = $this->_validate($postArray);
		if ($messageArray)
		{
			return $this->_error(array(
				'message' => $messageArray
			));
		}

		/* handle success */

		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init(uniqid());
		$createArray = array(
			'name' => $postArray['name'],
			'user' => $postArray['user'],
			'password' => $passwordHash->getHash(),
			'email' => $postArray['email'],
			'language' => $this->_registry->get('language'),
			'groups' => Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id,
			'status' => Db::getSetting('verification') ? 0 : 1
		);
		$mailArray = array(
			'name' => $postArray['name'],
			'user' => $postArray['user'],
			'password' => $passwordHash->getRaw(),
			'email' => $postArray['email']
		);

		/* create and mail */

		if ($this->_create($createArray) && $this->_mail($mailArray))
		{
			return $this->_success(array(
				'message' => Db::getSetting('verification') ? $this->_language->get('registration_verification') : $this->_language->get('registration_sent')
			));
		}
		return $this->_error(array(
			'message' => $this->_language->get('something_wrong')
		));
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

	protected function _success($successArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setUrl($this->_language->get('login'), 'login')
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

	protected function _error($errorArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setUrl($this->_language->get('back'), 'register')
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

	protected function _validate($postArray = array())
	{
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();

		/* validate post */

		$messageArray = array();
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
		if (Db::getSetting('captcha') > 0 && $captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
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
	 * @return boolean
	 */

	protected function _create($createArray = array())
	{
		return Db::forTablePrefix('users')
			->create()
			->set(array(
				'name' => $createArray['name'],
				'user' => $createArray['user'],
				'email' => $createArray['email'],
				'password' => $createArray['password'],
				'language' => $createArray['language'],
				'groups' => $createArray['groups'],
				'status' => $createArray['status']
			))
			->save();
	}

	/**
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return boolean
	 */

	protected function _mail($mailArray = array())
	{
		$urlLogin = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login';

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement
			->init('a', array(
				'href' => $urlLogin
			))
			->text($urlLogin);

		/* prepare mail */

		$toArray = array(
			$mailArray['name'] => $mailArray['email'],
			Db::getSetting('author') => Db::getSetting('notification') ? Db::getSetting('email') : null
		);
		$fromArray = array(
			$mailArray['name'] => $mailArray['email']
		);
		$subject = $this->_language->get('registration');
		$bodyArray = array(
			'<strong>' . $this->_language->get('name') . $this->_language->get('colon') . '</strong> ' . $mailArray['name'],
			'<br />',
			'<strong>' . $this->_language->get('user') . $this->_language->get('colon') . '</strong> ' . $mailArray['user'],
			'<br />',
			'<strong>' . $this->_language->get('password') . $this->_language->get('colon') . '</strong> ' . $mailArray['password'],
			'<br />',
			'<strong>' . $this->_language->get('login') . $this->_language->get('colon') . '<strong> ' . $linkElement
		);

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}