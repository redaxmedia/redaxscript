<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Installer;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
use Redaxscript\Validator;
use Redaxscript\View;

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
	 * instance of the config
	 *
	 * @var object
	 */

	public $_config;

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		/* process post */

		$postArray = $this->_processPost();

		if ($this->_checkInstall($postArray) === 1)
		{
			return $this->_success(array(
				'redirect' => $this->_registry->get('root'),
				'time' => 2,
				'title' => $this->_language->get('installation_completed')
			));
		}
		else if ($this->_request->getPost('Redaxscript\View\InstallForm'))
		{
			if (!$this->_validate($postArray))
			{
				$errorArray[] = $this->_language->get('something_wrong');
			}

			/* process error */

			if (!$errorArray)
			{
				return $this->_error($errorArray);
			}

			$this->_write($postArray);
			$this->_install($postArray);

			if ($this->_mail($postArray))
			{
				return $this->_success(array(
					'redirect' => $this->_registry->get('root'),
					'time' => 2,
					'title' => $this->_language->get('installation_completed')
				));
			}
			else
			{
				$errorArray[] = $this->_language->get('something_wrong');
			}
		}
		else
		{
			$installNote = new View\InstallNote($this->_registry, $this->_language);
			return $installNote->render() . $this->_installForm($postArray);
		}

		return $this->_error($errorArray);
	}

	/**
	 * validate the postArray
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

		$errorArray = array();

		if ($postArray['dType'] != 'sqlite' && !$postArray['name'])
		{
			$errorArray[] = $this->_language->get('name_empty');
		}
		else if ($postArray['dType'] != 'sqlite' && !$postArray['user'])
		{
			$errorArray[] = $this->_language->get('user_empty');
		}
		else if ($postArray['dType'] != 'sqlite' && !$postArray['password'])
		{
			$errorArray[] = $this->_language->get('password_empty');
		}
		else if (!$postArray['email'])
		{
			$errorArray[] = $this->_language->get('email_empty');
		}
		else if ($loginValidator->validate($postArray['user']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('user_incorrect');
		}
		else if ($loginValidator->validate($postArray['password']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('password_incorrect');
		}
		else if ($emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('email_incorrect');
		}

		return $errorArray;
	}

	/**
	 * send login information
	 *
	 * @since 3.0.0
	 *
	 * @param $mailArray
	 *
	 * @return array
	 */

	private function _mail($mailArray = array())
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
	 * check if redaxscript is already install
	 *
	 * @since 3.0.0
	 *
	 * @param $postArray
	 *
	 * @return array
	 */

	private function _checkInstall($postArray = array())
	{
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();

		if ($this->_request->getPost('Redaxscript\View\InstallForm') && $this->_registry->get('dbStatus') && $postArray['name']
			&& $loginValidator->validate($postArray['user']) == Validator\ValidatorInterface::PASSED
			&& $loginValidator->validate($postArray['password']) == Validator\ValidatorInterface::PASSED
			&& $emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::PASSED
		)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * insert user into database via installer class
	 *
	 * @since 3.0.0
	 *
	 * @param $postArray
	 *
	 * @return array
	 */

	private function _install($postArray)
	{
		$installer = new Installer($this->_config);
		$installer->init();
		$installer->rawDrop();
		$installer->rawCreate();
		$installer->insertData(array(
			'adminName' => $postArray['name'],
			'adminUser' => $postArray['user'],
			'adminPassword' => $postArray['password'],
			'adminEmail' => $postArray['email']
		));
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
		$config = $this->_config;
		$config->set('dbType', $writeArray['dType']);
		$config->set('dbHost', $writeArray['dHost']);
		$config->set('dbName', $writeArray['dName']);
		$config->set('dbUser', $writeArray['dUser']);
		$config->set('dbPassword', $writeArray['dPassword']);
		$config->set('dbPrefix', $writeArray['dPrefix']);
		$config->set('dbSalt', $writeArray['dSalt']);
		$config->write();
	}

	/**
	 * show InstallForm
	 *
	 * @since 3.0.0
	 *
	 * @param $postArray
	 *
	 * @return array
	 */

	private function _installForm($postArray = array())
	{
		$installForm = new View\InstallForm($this->_registry, $this->_language);
		return $installForm->render(array(
			'dbType' => $postArray['dType'],
			'dbHost' => $postArray['dHost'],
			'dbName' => $postArray['dName'],
			'dbUser' => $postArray['dUser'],
			'dbPassword' => $postArray['dPassword'],
			'dbPrefix' => $postArray['dPrefix'],
			'adminName' => $postArray['name'],
			'adminUser' => $postArray['user'],
			'adminPassword' => $postArray['password'],
			'adminEmail' => $postArray['email']
		));
	}

	/**
	 * return post parameters as array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	private function _processPost()
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();

		return array(
			'dType' => $specialFilter->sanitize($this->_request->getPost('db-type')),
			'dHost' => $specialFilter->sanitize($this->_request->getPost('db-host')),
			'dName' => $specialFilter->sanitize($this->_request->getPost('db-name')),
			'dUser' => $specialFilter->sanitize($this->_request->getPost('db-user')),
			'dPassword' => $specialFilter->sanitize($this->_request->getPost('db-password')),
			'dPrefix' => $specialFilter->sanitize($this->_request->getPost('db-prefix')),
			'dSalt' => $specialFilter->sanitize($this->_request->getPost('db-salt')),
			'name' => $specialFilter->sanitize($this->_request->getPost('admin-name')),
			'user' => $specialFilter->sanitize($this->_request->getPost('admin-user')),
			'password' => $specialFilter->sanitize($this->_request->getPost('admin-password')),
			'email' => $emailFilter->sanitize($this->_request->getPost('admin-email'))
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
		return $messenger->setAction($this->_language->get('home'), $successArray['redirect'])->doRedirect($successArray['time'])->success($successArray['title']);
		// ->setAction($this->_registry->get('root'))->success($this->_language->get('installation_completed'))
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param $errorArray
	 *
	 * @return string
	 */

	protected function _error($errorArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->error($errorArray, $this->_language->get('alert'));
	}
}