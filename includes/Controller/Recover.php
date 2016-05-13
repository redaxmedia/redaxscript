<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Registry;
use Redaxscript\Request;
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

class Recover implements ControllerInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param Request $request instance of the request class
	 */

	public function __construct(Registry $registry, Language $language, Request $request)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_request = $request;
	}

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 */

	public function process()
	{
		$emailFilter = new Filter\Email();
		$emailValidator = new Validator\Email();
		$captchaValidator = new Validator\Captcha();

		/* process post */

		$postArray = array(
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* validate post */

		if (!$postArray['email'])
		{
			$errorArray[] = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('email_incorrect');
		}
		else if (!Db::forTablePrefix('users')->where('email', $postArray['email'])->findOne()->id)
		{
			$errorArray[] = $this->_language->get('email_unknown');
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

		$users = Db::forTablePrefix('users')->where(array(
			'email' => $postArray['email'],
			'status' => 1
		))->findMany();

		/* process users */

		foreach ($users as $user)
		{
			$mailArray = array(
				'id' => $user->id,
				'name' => $user->name,
				'user' => $user->user,
				'password' => $user->password,
				'email' => $user->email
			);
			if ($this->_mail($mailArray))
			{
				$successArray[] = $user->name . $this->_language->get('colon') . ' ' . $this->_language->get('recovery_sent');
			}
		}
		if ($successArray)
		{
			return $this->success($successArray);
		}
		return $this->error($this->_language->get('something_wrong'));
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

	public function success($successArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('login'), 'login')->doRedirect()->success($successArray, $this->_language->get('operation_completed'));
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
		return $messenger->setAction($this->_language->get('back'), 'login/recover')->error($errorArray, $this->_language->get('error_occurred'));
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

	protected function _mail($mailArray = array())
	{
		$urlReset = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login/reset/' . sha1($mailArray['password']) . '/' . $mailArray['id'];

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement
			->init('a', array(
				'href' => $urlReset
			))
			->text($urlReset);

		/* prepare mail */

		$toArray = array(
			$mailArray['name'] => $mailArray['email']
		);
		$fromArray = array(
			Db::getSetting('author') => Db::getSetting('email')
		);
		$subject = $this->_language->get('recovery');
		$bodyArray = array(
			'<strong>' . $this->_language->get('user') . $this->_language->get('colon') . '</strong> ' . $mailArray['user'],
			'<br />',
			'<strong>' . $this->_language->get('password_reset') . $this->_language->get('colon') . '</strong> ' . $linkElement
		);

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}