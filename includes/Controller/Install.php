<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

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
			'adminName' => $specialFilter->sanitize($this->_request->getPost('admin-name')),
			'adminUser' => $specialFilter->sanitize($this->_request->getPost('admin-user')),
			'adminPassword' => $specialFilter->sanitize($this->_request->getPost('admin-password')),
			'adminEmail' => $emailFilter->sanitize($this->_request->getPost('admin-email'))
		);

		/* handle error */

		$messageArray = $this->_validateDatabase($postArray);
		if ($messageArray)
		{
			return $this->_error(array(
				'title' => Language::get('database'),
				'message' => $messageArray
			));
		}
		$messageArray = $this->_validateAccount($postArray);
		if ($messageArray)
		{
			return $this->_error(array(
				'title' => Language::get('account'),
				'message' => $messageArray
			));
		}
		$dbArray = array(
			'dbType' => $postArray['db-type'],
			'dbHost' => $postArray['db-host'],
			'dbName' => $postArray['db-name'],
			'dbUser' => $postArray['db-user'],
			'dbPassword' => $postArray['db-password'],
			'dbPrefix' => $postArray['db-prefix'],
			'dbSalt' => $postArray['db-salt']
		);
		$adminArray = array(
			'adminUser' => $postArray['adminUser'],
			'adminName' => $postArray['adminName'],
			'adminEmail' => $postArray['adminEmail'],
			'adminPassword' => $postArray['adminPassword']
		);

		/* write config */

		if (!$this->_write($dbArray))
		{
			return $this->_error(array(
				'message' => $this->_language->get('file_permission_grant') . $this->_language->get('colon') . ' config.php'
			));
		}

		/* install database */

		if (!$this->_install($adminArray))
		{
			return $this->_error(array(
				'message' => $this->_language->get('something_wrong')
			));
		}

		/* handle success */

		if (!$this->_mail($adminArray))
		{
			return $this->_error(array(
				'message' => $this->_language->get('something_wrong'),
				'redirect' => $this->_registry->get('root'),
				'timeout' => 5
			));
		}
		return $this->_success(array(
				'message' => $this->_language->get('installation_completed', '_installation'),
				'redirect' => $this->_registry->get('root'),
				'timeout' => 5
			)
		);
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
		return $messenger
			->setAction($this->_language->get('home'), $successArray['redirect'])
			->doRedirect($successArray['timeout'])
			->success($successArray['message'], $successArray['title']);
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
			->setAction($this->_language->get('home'), $errorArray['redirect'])
			->error($errorArray['message'], $errorArray['title']);
	}

	/**
	 * validate the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array to be validated
	 *
	 * @return array
	 */

	protected function _validateDatabase($postArray = array())
	{
		$messageArray = array();
		if (!$postArray['dbType'])
		{
			$messageArray[] = $this->_language->get('type_empty');
		}
		if (!$postArray['dbHost'])
		{
			$messageArray[] = $this->_language->get('host_empty');
		}
		if ($postArray['dbType'] !== 'sqlite')
		{
			if (!$postArray['dbName'])
			{
				$messageArray[] = $this->_language->get('name_empty');
			}
			if (!$postArray['dbUser'])
			{
				$messageArray[] = $this->_language->get('user_empty');
			}
		}
		return $messageArray;
	}

	/**
	 * validate the account
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array to be validated
	 *
	 * @return array
	 */

	protected function _validateAccount($postArray = array())
	{
		$emailValidator = new Validator\Email();
		$loginValidator = new Validator\Login();

		/* validate post */

		$messageArray = array();
		if (!$postArray['adminName'])
		{
			$messageArray[] = $this->_language->get('name_empty');
		}
		if (!$postArray['adminUser'])
		{
			$messageArray[] = $this->_language->get('user_empty');
		}
		else if ($loginValidator->validate($postArray['adminUser']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['adminPassword'])
		{
			$messageArray[] = $this->_language->get('password_empty');
		}
		else if ($loginValidator->validate($postArray['adminPassword']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('password_incorrect');
		}
		if (!$postArray['adminEmail'])
		{
			$messageArray[] = $this->_language->get('email_empty');
		}
		else if ($emailValidator->validate($postArray['adminEmail']) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('email_incorrect');
		}
		return $messageArray;
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
	 * install the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $installArray
	 *
	 * @return array
	 */

	protected function _install($installArray = array())
	{
		Db::construct($this->_config);
		Db::init();

		/* installer */

		$installer = new Installer($this->_config);
		$installer->init();
		$installer->rawDrop();
		$installer->rawCreate();
		if ($installArray)
		{
			$installer->insertData(array(
				'adminName' => $installArray['name'],
				'adminUser' => $installArray['user'],
				'adminPassword' => $installArray['password'],
				'adminEmail' => $installArray['email']
			));
			return Db::getStatus() === 2;
		}
		return false;
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
			'<strong>' . $this->_language->get('user') . $this->_language->get('colon') . '</strong> ' . $mailArray['adminUser'],
			'<br />',
			'<strong>' . $this->_language->get('password') . $this->_language->get('colon') . '</strong> ' . $mailArray['adminPassword'],
			'<br />',
			'<strong>' . $this->_language->get('url') . $this->_language->get('colon') . '</strong> ' . $linkElement
		);

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
	}
}