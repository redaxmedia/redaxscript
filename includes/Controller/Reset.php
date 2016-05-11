<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Hash;
use Redaxscript\Html\Element;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Filter;
use Redaxscript\Registry;
use Redaxscript\Request;
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

class Reset implements ControllerInterface
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
		$specialFilter = new Filter\Special();
		$captchaValidator = new Validator\Captcha();

		/* process post */

		$postArray = array(
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'password' => $specialFilter->sanitize($this->_request->getPost('password')),
			'task' => $this->_request->getPost('task'),
			'solution' => $this->_request->getPost('solution')
		);

		/* fetch user */

		$user = Db::forTablePrefix('users')->where(array(
			'id' => $postArray['id'],
			'status' => 1
		))->findOne();

		/* validate post */

		if (!$postArray['id'])
		{
			$errorArray[] = $this->_language->get('user_empty');
		}
		else if (!$user->id)
		{
			$errorArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['password'])
		{
			$errorArray[] = $this->_language->get('password_empty');
		}
		else if (sha1($user->password) !== $postArray['password'])
		{
			$errorArray[] = $this->_language->get('password_incorrect');
		}
		if ($captchaValidator->validate($postArray['task'], $postArray['solution']) === Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('captcha_incorrect');
		}

		/* handle error */

		if ($errorArray)
		{
			return $this->error($errorArray);
		}

		/* handle success */

		$passwordHash = new Hash(Config::getInstance());
		$passwordHash->init(uniqid());
		$resetArray = array(
			'id' => $user->id,
			'password' => $passwordHash->getHash()
		);
		$mailArray = array(
			'name' => $user->name,
			'email' => $user->email,
			'password' => $passwordHash->getRaw()
		);

		/* reset and mail */

		if ($this->_reset($resetArray) && $this->_mail($mailArray))
		{
			return $this->success();
		}
		return $this->error($this->_language->get('something_wrong'));
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function success()
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('login'), 'login')->doRedirect()->success($this->_language->get('password_sent'), $this->_language->get('operation_completed'));
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
	 * reset the password
	 *
	 * @since 3.0.0
	 *
	 * @param $resetArray array of the reset
	 *
	 * @return boolean
	 */

	protected function _reset($resetArray = array())
	{
		return Db::forTablePrefix('users')
			->where(array(
				'id' => $resetArray['id'],
				'status' => 1
			))
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

	protected function _mail($mailArray = array())
	{
		$urlReset = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login';

		/* html elements */

		$linkElement = new Element();
		$linkElement
			->init('a', array(
				'href' => $urlReset,
				'class' => 'link-result'
			))
			->text($urlReset);

		/* prepare mail */

		$toArray = array(
			$mailArray['name'] => $mailArray['email']
		);
		$fromArray = array(
			Db::getSetting('author') => Db::getSetting('email')
		);
		$subject = $this->_language->get('password_new');
		$bodyArray = array(
			'<strong>' . $this->_language->get('password_new') . $this->_language->get('colon') . '</strong> ' . $mailArray['password'],
			'<br />',
			'<strong>' . $this->_language->get('login') . $this->_language->get('colon') . '</strong> ' . $linkElement
		);

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}