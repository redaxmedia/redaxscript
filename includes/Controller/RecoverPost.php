<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Filter;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

/**
 * children class to reset password post request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class RecoverPost implements ControllerInterface
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
	 * @param Request $request instance of the registry class
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
			$errorArray[] = Language::get('email_empty');
		}
		else if ($emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = Language::get('email_incorrect');
		}
		else if (!Db::forTablePrefix('users')->where('email', $postArray['email'])->findOne()->id)
		{
			$errorArray[] = Language::get('email_unknown');
		}
		if ($captchaValidator->validate($postArray['task'], $postArray['solution']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = Language::get('captcha_incorrect');
		}

		/* handle error */

		if ($errorArray)
		{
			return self::error($errorArray);
		}

		/* handle success */

		else
		{
			return self::success(array(
				'name' => $postArray['name'],
				'user' => $postArray['user'],
				'email' => $postArray['email'],
				'language' => $this->_registry->get('language'),
				'groups' => Db::forTablePrefix('groups')->where('alias', 'members')->findOne()->id,
				'status' => Db::getSettings('verification') ? 0 : 1
			));
		}
	}

	/**
	 * handle success
	 *
	 * @since 3.0.0
	 *
	 * @param array $successArray
	 *
	 * @return string
	 */

	public function success($successArray = array())
	{
		/* query users */

		$user = Db::forTablePrefix('users')->where(array(
			'email' => $successArray['email'],
			'status' => 1
		))->findArray();
		if ($user)
		{
			/* send reminder information */

			$passwordResetRoute = $this->_registry->get('root') . '/' . $this->_registry->get('rewriteRoute') . 'login/reset/' . sha1($user->password) . '/' . $user->id;
			$passwordResetLink = '<a href="' . $passwordResetRoute . '">' . $passwordResetRoute . '</a>';
			$toArray = array(
				$user->name => $user->email
			);
			$fromArray = array(
				Db::getSettings('author') => Db::getSettings('email')
			);
			$subject = Language::get('recovery');
			$bodyArray = array(
				'<strong>' . Language::get('user') . Language::get('colon') . '</strong> ' . $user->user,
				'<br />',
				'<strong>' . Language::get('password_reset') . Language::get('colon') . '</strong> ' . $passwordResetLink
			);

			/* mailer object */

			$mailer = new Mailer();
			$mailer->init($toArray, $fromArray, $subject, $bodyArray);
			$mailer->send();
		}

		$messenger = new Messenger();
		return $messenger->setAction(Language::get('login'), 'login')->doRedirect()->success(Language::get('recovery_sent'), Language::get('operation_completed'));
	}

	/**
	 * handle error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction(Language::get('back'), 'recovery')->error($errorArray, Language::get('error_occurred'));
	}
}