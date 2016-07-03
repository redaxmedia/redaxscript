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

		$this->_config = Config::getInstance();
		$postArray = $this->_processPost();

		/* install */

		// if redaxscript has been installed already, redirect the use to the home page
		if (Db::getStatus() === 2) // 1 === db connect, 2 === tables installed
		{
			// without the extra meta tag, it will redirect to root/?p=
			return $this->_success(array(
				'title' => $this->_language->get('installation_completed')
			)) . "<meta http-equiv=\"refresh\" content=\"2; url=" . $this->_registry->get('root') . "\" />";
		}

		// config file is written, but not the db
		if (Db::getStatus() === 1)
		{
			// get the saved post data from session
			if (!$_SESSION['install'])
			{
				$messageArray[] = $this->_language->get('something_wrong');
			}
			else
			{
				$postArray = $_SESSION['install'];

				if ($this->_install($postArray))
				{
					return $this->_error(array(
						'description' => $this->_language->get('something_wrong'),
						'redirect' => '/'
					));
				}

				if ($this->_mail($postArray))
				{
					unset($_SESSION['install']);
					return "<meta http-equiv=\"refresh\" content=\"0; url=" . $this->_registry->get('root') . "\" />";
				}
				else
				{
					return $this->_error(array(
						'description' => $this->_language->get('something_wrong') . " - couldn't send Email"
					)) . "<meta http-equiv=\"refresh\" content=\"0; url=\" />";
				}
			}

			if (!$messageArray)
			{
				return "<meta http-equiv=\"refresh\" content=\"0; url=" . $this->_registry->get('root') . "\" />";
			}

			return $this->_error(array(
				'description' => $messageArray,
				'redirect' => '/'
			));
		}

		if (Db::getStatus() === 0 && $this->_request->getPost('Redaxscript\View\InstallForm'))
		{
			/* handle error */

			$messageArray = $this->_validate($postArray);
			if ($messageArray)
			{
				return $this->_error($messageArray);
			}

			if ($this->_write($postArray))
			{
				// TODO: make $this->install work here. Current solution is temporary

				$_SESSION['install'] = $postArray;

				// $this->_install($postArray);
				return $this->_success(array(
					'redirect' => '/install.php',
					'time' => 0
				));
			}

			return $this->_error(array(
				'description' => $this->_language->get('something_wrong')
			));
		}

		$installNote = new View\InstallNote($this->_registry, $this->_language);
		return $installNote->render() . $this->_installForm($postArray);
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

		$messageArray = array();

		if ($postArray['dType'] != 'sqlite' && !$postArray['name'])
		{
			$messageArray[] = $this->_language->get('name_empty');
		}
		else if ($postArray['dType'] != 'sqlite' && !$postArray['user'])
		{
			$messageArray[] = $this->_language->get('user_empty');
		}
		else if ($postArray['dType'] != 'sqlite' && !$postArray['password'])
		{
			$messageArray[] = $this->_language->get('password_empty');
		}
		else if (!$postArray['email'])
		{
			$messageArray[] = $this->_language->get('email_empty');
		}
		else if ($loginValidator->validate($postArray['user']) == Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('user_incorrect');
		}
		else if ($loginValidator->validate($postArray['password']) == Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('password_incorrect');
		}
		else if ($emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('email_incorrect');
		}

		return $messageArray;
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
			'dbType' => $postArray['dbType'],
			'dbHost' => $postArray['dbHost'],
			'dbName' => $postArray['dbName'],
			'dbUser' => $postArray['dbUser'],
			'dbPassword' => $postArray['dbPassword'],
			'dbPrefix' => $postArray['dbPrefix'],
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
	 * @param $errorArray
	 *
	 * @return string
	 */

	protected function _error($errorArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->setAction($this->_language->get('home'), $errorArray['redirect'])->error($errorArray['description'], $this->_language->get('alert'));
	}
}