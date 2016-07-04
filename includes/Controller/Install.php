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
/*TODO: I would empty the process method and start from scratch via TDD - refactoring it is hopeless at that point */
class Install extends ControllerAbstract
{
	/* TODO: Remove this bad coding style, use dependency injection instead */
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
			/* TODO: Remove this redirect, I want to view the installForm even if the DB ist ready */
			return $this->_success(array(
				'title' => $this->_language->get('installation_completed')
			)) . '<meta http-equiv="refresh" content="2; url="' . $this->_registry->get('root') . ' />';
		}

		// config file is written, but not the db
		if (Db::getStatus() === 1)
		{
			// @TODO: remove the $_SESSION and raw HTML meta redirects
			if (!$_SESSION['install'])
			{
				$messageArray[] = $this->_language->get('something_wrong');
			}
			else
			{
				$postArray = $_SESSION['install'];

				if ($this->_install($postArray))
				{
					/*TODO: key should be called message and not description */
					return $this->_error(array(
						'description' => $this->_language->get('something_wrong'),
						'redirect' => '/'
					));
				}

				if ($this->_mail($postArray))
				{
					unset($_SESSION['install']);
					return '<meta http-equiv="refresh" content="0; url="' . $this->_registry->get('root') . ' />';
				}
				else
				{
					/*TODO: key should be called message and not description */
					return $this->_error(array(
						'description' => $this->_language->get('something_wrong') . " - couldn't send Email"
					)) . '<meta http-equiv="refresh" content="0; />';
				}
			}

			if (!$messageArray)
			{
				return '<meta http-equiv="refresh" content="0; url="' . $this->_registry->get('root') . ' />';
			}
			/*TODO: key should be called message and not description */
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

		/* TODO: Move installNote / installStatus rendering to the router.php */
		$installNote = new View\InstallStatus($this->_registry, $this->_language);
		return $installNote->render() . $this->_installForm($postArray);
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
		/*TODO: mixed usage of dType vs dbType etc. */
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
	 * send the mail
	 *
	 * @since 3.0.0
	 *
	 * @param array $mailArray
	 *
	 * @return boolean
	 */

	/* TODO: Why private again? */
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
	 * install the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $installArray
	 *
	 * @return array
	 */

	/* TODO: Why private again? I think this method should also handle the reinit of the Db class to use the latest Config instance */
	private function _install($installArray = array())
	{
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
	 * @param array $postArray
	 *
	 * @return array
	 */

	/*TODO: Why private again? that whole function is not needed because the router does the job of displaying a form */
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

	/* TODO: Why private again? Move this to the top of the process method, as this is the common way we did it in each controller */
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
	 * @param array $errorArray
	 *
	 * @return string
	 */

	protected function _error($errorArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->setAction($this->_language->get('home'), $errorArray['redirect'])->error($errorArray['description'], $this->_language->get('alert'));
	}
}