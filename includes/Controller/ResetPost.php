<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Hash;
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

class ResetPost implements ControllerInterface
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
		$specialFilter = new Filter\Special();
		$captchaValidator = new Validator\Captcha();

		/* process post */

		$postArray = array(
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution'),
			'new_password' => uniqid()
		);

		/* query user information */

		if ($postArray['id'] && $postArray['post_password'])
		{
			$user = Db::forTablePrefix('users')->where(array(
				'id' => $postArray['id'],
				'status' => 1
			))->findArray();
		}

		/* validate post */

		if (!$postArray['id'] || !$postArray['post_password'])
		{
			$errorArray[] = Language::get('input_incorrect');
		}
		if ($captchaValidator->validate($postArray['task'], $postArray['solution']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = Language::get('captcha_incorrect');
		}
		if (!$user->id || sha1($user->password) != $postArray['post_password'])
		{
			$errorArray[] = Language::get('access_no');
		}


		/* handle error */

		if ($errorArray)
		{
			$errorArray['post_id'] = $postArray['id'];
			$errorArray['post_password'] = $postArray['post_password'];
			return self::error($errorArray);
		}

		/* handle success */

		else
		{
			return self::success(array(
				'id' => $postArray['id'],
				'password' => $postArray['password'],
				'name' => $user->name,
				'email' => $user->email
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
		/* send new password */

		$loginRoute = $this->_registry->get('root') . '/' . $this->_registry->get('rewriteRoute') . 'login';

		/* html element */

		$linkElement = new Element();
		$linkElement
			->init('a', array(
				'href' => Registry::get('rewriteRoute') . $loginRoute,
				'class' => 'link-result'
			))
			->text($loginRoute);

		$toArray = array(
			$successArray['name'] => $successArray['email']
		);
		$fromArray = array(
			Db::getSettings('author') => Db::getSettings('email')
		);
		$subject = Language::get('password_new');
		$bodyArray = array(
			'<strong>' . Language::get('password_new') . Language::get('colon') . '</strong> ' . $successArray['password'],
			'<br />',
			'<strong>' . Language::get('login') . Language::get('colon') . '</strong> ' . $linkElement
		);

		/* mailer object */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		$mailer->send();

		/* update password */

		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init($successArray['new_password']);
		Db::forTablePrefix('users')
			->where(array(
				'id' => $successArray['id'],
				'status' => 1
			))
			->findOne()
			->set('password', $passwordHash->getHash())
			->save();

		$messenger = new Messenger();
		return $messenger->setAction(Language::get('login'), 'login')->doRedirect()->success(Language::get('password_sent'), Language::get('operation_completed'));
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

		if ($errorArray['post_id'] && $errorArray['$post_password'])
		{
			$back_route = 'login/reset/' . $errorArray['$post_password'] . '/' . $errorArray['post_id'];
		}
		else
		{
			$back_route = 'login/recover';
		}

		unset($errorArray['post_id']);
		unset($errorArray['post_password']);

		/* show error */

		return $messenger->setAction(Language::get('back'), $back_route)->error($errorArray, Language::get('error_occurred'));

	}
}