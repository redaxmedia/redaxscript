<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Installer;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Validator;
use Redaxscript\View;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * children class to process install
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 *
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Install extends ControllerAbstract
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */
	protected $_config;

	/**
	 * construct of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry
	 * @param Language $language
	 * @param Request $request
	 * @param Config $config
	 */

	public function __construct(Registry $registry, Language $language, Request $request, Config $config)
	{
		parent::__construct($registry, $language, $request);

		$this->_config = $config;
	}

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
		$emailFilter = new Filter\Email();

		/* process post */

		$postArray = array(
			'dbType' => $this->_request->getPost('db-type'),
			'dbHost' => $this->_request->getPost('db-host'),
			'dbName' => $this->_request->getPost('db-name'),
			'dbUser' => $this->_request->getPost('db-user'),
			'dbPassword' => $this->_request->getPost('db-password'),
			'dbPrefix' => $this->_request->getPost('db-prefix'),
			'dbSalt' => $this->_request->getPost('db-salt'),
			'name' => $specialFilter->sanitize($this->_request->getPost('admin-name')),
			'user' => $specialFilter->sanitize($this->_request->getPost('admin-user')),
			'password' => $specialFilter->sanitize($this->_request->getPost('admin-password')),
			'email' => $emailFilter->sanitize($this->_request->getPost('admin-email'))
		);

		/* handle error */

		$messageArray = $this->_validate($postArray);
		if ($messageArray)
		{
			return $this->_error(array(
				'message' => $messageArray
			));
		}

		/* write config file */

		if (!$this->_write($postArray))
		{
			return $this->_error(array(
				'message' => $this->_language->get('something_wrong')
			));
		}

		/* write database */

		if (!$this->_install($postArray))
		{
			return $this->_error(array(
				'message' => $this->_language->get('something_wrong')
			));
		}

		$mailArray = array(
			'user' => $postArray['user'],
			'name' => $postArray['name'],
			'email' => $postArray['email'],
			'password' => $postArray['password']
		);

		/* send mail */

		if (!$this->_mail($mailArray))
		{
			return $this->_error(array(
				'message' => $this->_language->get('something_wrong'),
				'redirect' => $this->_registry->get('root'),
				'time' => 5
			));
		}

		return $this->_success(array(
				'redirect' => $this->_registry->get('root'),
				'time' => 0
			)
		);
	}

	/**
	 * validate
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array to be validated
	 *
	 * @return array
	 */

	protected function _validate($postArray = array())
	{
		$emailValidator = new Validator\Email();
		$loginValidator = new Validator\Login();

		/* validate post */

		$messageArray = array();

		if ($postArray['dbType'] != 'sqlite' && !$postArray['name'])
		{
			$messageArray[] = $this->_language->get('name_empty');
		}
		if ($postArray['dbType'] != 'sqlite' && !$postArray['user'])
		{
			$messageArray[] = $this->_language->get('user_empty');
		}
		else if ($loginValidator->validate($postArray['user']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('user_incorrect');
		}
		if ($postArray['dbType'] != 'sqlite' && !$postArray['password'])
		{
			$messageArray[] = $this->_language->get('password_empty');
		}
		else if ($loginValidator->validate($postArray['password']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('password_incorrect');
		}
		if (!$postArray['email'])
		{
			$messageArray[] = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postArray['email']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('email_incorrect');
		}

		return $messageArray;
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
	// TODO: fix : $this->_registry is empty
	protected function _mail($mailArray = array())
	{
		$mailer = new Mailer();

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement
			->init('a', array(
				'href' => $this->_registry->get('root')
			))
			->text($this->_registry->get('root'));

		/* prepare mail */

		$toArray = array(
			$mailArray['name'] => $mailArray['email']
		);
		$fromArray = array(
			Db::getSetting('author') => Db::getSetting('email')
		);
		$subject = $this->_language->get('installation');
		$bodyArray = array(
			'<strong>' . $this->_language->get('user') . $this->_language->get('colon') . '</strong> ' . $mailArray['user'],
			'<br />',
			'<strong>' . $this->_language->get('password') . $this->_language->get('colon') . '</strong> ' . $mailArray['password'],
			'<br />',
			'<strong>' . $this->_language->get('url') . $this->_language->get('colon') . '</strong> ' . $linkElement
		);

		/* send mail */

		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}

	/**
	 * install the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $installArray
	 *
	 * @return array
	 */

	/* TODO: I think this method should also handle the reinit of the Db class to use the latest Config instance */
	protected function _install($installArray = array())
	{
		Db::construct($this->_config);
		Db::init();
		Db::rawInstance();

		$installer = new Installer($this->_config);
		$installer->init();
		$installer->rawDrop();
		$installer->rawCreate();
		$installer->insertData(array(
			'adminName' => $installArray['name'],
			'adminUser' => $installArray['user'],
			'adminPassword' => $installArray['password'],
			'adminEmail' => $installArray['email']
		));

		if (Db::forTablePrefix('users')->where('user', $installArray['user'])->findOne())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * write config file
	 *
	 * @since 3.0.0
	 *
	 * @param array $writeArray
	 *
	 * @return array
	 */

	protected function _write($writeArray = array())
	{
		$this->_config->set('dbType', $writeArray['dbType']);
		$this->_config->set('dbHost', $writeArray['dbHost']);
		$this->_config->set('dbName', $writeArray['dbName']);
		$this->_config->set('dbUser', $writeArray['dbUser']);
		$this->_config->set('dbPassword', $writeArray['dbPassword']);
		$this->_config->set('dbPrefix', $writeArray['dbPrefix']);
		$this->_config->set('dbSalt', $writeArray['dbSalt']);

		return $this->_config->write();
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

	protected function _success($successArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->setAction($this->_language->get('home'), $successArray['redirect'])->doRedirect($successArray['time'])->success($successArray['title']);
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
		return $messenger->setAction($this->_language->get('home'), $errorArray['redirect'])->error($errorArray['message'], $this->_language->get('alert'));
	}
}