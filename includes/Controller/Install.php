<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Installer;
use Redaxscript\Mailer;
use Redaxscript\Model;
use Redaxscript\Validator;
use function touch;
use function unlink;

/**
 * children class to process install
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Install extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process() : string
	{
		$postArray = $this->_normalizePost($this->_sanitizePost());

		/* validate database */

		$validateArray = $this->_validateDatabase($postArray);
		if ($validateArray)
		{
			return $this->_error(
			[
				'url' => 'install.php',
				'title' => $this->_language->get('database'),
				'message' => $validateArray
			]);
		}

		/* validate account */

		$validateArray = $this->_validateAccount($postArray);
		if ($validateArray)
		{
			return $this->_error(
			[
				'url' => 'install.php',
				'title' => $this->_language->get('account'),
				'message' => $validateArray
			]);
		}

		/* touch config */

		$configArray =
		[
			'dbType' => $postArray['dbType'],
			'dbHost' => $postArray['dbHost'],
			'dbName' => $postArray['dbName'],
			'dbUser' => $postArray['dbUser'],
			'dbPassword' => $postArray['dbPassword'],
			'dbPrefix' => $postArray['dbPrefix']
		];
		if (!$this->_touch($configArray))
		{
			return $this->_error(
			[
				'url' => 'install.php',
				'message' => $this->_language->get('directory_permission_grant') . $this->_language->get('point')
			]);
		}

		/* write config */

		if (!$this->_write($configArray))
		{
			return $this->_error(
			[
				'url' => 'install.php',
				'message' => $this->_language->get('file_permission_grant') . $this->_language->get('colon') . ' config.php'
			]);
		}

		/* refresh connection */

		if ($postArray['refreshConnection'])
		{
			$this->_refreshConnection();
		}

		/* handle database */

		if (!$this->_getStatus())
		{
			return $this->_error(
			[
				'url' => 'install.php',
				'message' => $this->_language->get('database_failed')
			]);
		}

		/* handle install */

		$adminArray =
		[
			'adminName' => $postArray['adminName'],
			'adminUser' => $postArray['adminUser'],
			'adminEmail' => $postArray['adminEmail'],
			'adminPassword' => $postArray['adminPassword']
		];
		if (!$this->_install($adminArray))
		{
			return $this->_error(
			[
				'url' => 'install.php',
				'message' => $this->_language->get('installation_failed')
			]);
		}

		/* handle mail */

		$mailArray =
		[
			'adminName' => $postArray['adminName'],
			'adminUser' => $postArray['adminUser'],
			'adminEmail' => $postArray['adminEmail']
		];
		if (!$this->_mail($mailArray))
		{
			return $this->_warning(
			[
				'url' => 'index.php',
				'message' => $this->_language->get('email_failed')
			]);
		}

		/* handle success */

		return $this->_success(
		[
			'url' => 'index.php',
			'message' => $this->_language->get('installation_completed')
		]);
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$emailFilter = new Filter\Email();
		$nameFilter = new Filter\Name();
		$passwordFilter = new Filter\Password();
		$userFilter = new Filter\User();

		/* sanitize post */

		return
		[
			'dbType' => $this->_request->getPost('db-type'),
			'dbHost' => $this->_request->getPost('db-host'),
			'dbName' => $this->_request->getPost('db-name'),
			'dbUser' => $this->_request->getPost('db-user'),
			'dbPassword' => $this->_request->getPost('db-password'),
			'dbPrefix' => $this->_request->getPost('db-prefix'),
			'adminName' => $nameFilter->sanitize($this->_request->getPost('admin-name')),
			'adminUser' => $userFilter->sanitize($this->_request->getPost('admin-user')),
			'adminPassword' => $passwordFilter->sanitize($this->_request->getPost('admin-password')),
			'adminEmail' => $emailFilter->sanitize($this->_request->getPost('admin-email')),
			'refreshConnection' => $this->_request->getPost('refresh-connection')
		];
	}

	/**
	 * validate the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validateDatabase(array $postArray = []) : array
	{
		$validateArray = [];
		if (!$postArray['dbType'])
		{
			$validateArray[] = $this->_language->get('type_empty');
		}
		if (!$postArray['dbHost'])
		{
			$validateArray[] = $this->_language->get('host_empty');
		}
		if ($postArray['dbType'] !== 'sqlite')
		{
			if (!$postArray['dbName'])
			{
				$validateArray[] = $this->_language->get('name_empty');
			}
			if (!$postArray['dbUser'])
			{
				$validateArray[] = $this->_language->get('user_empty');
			}
		}
		return $validateArray;
	}

	/**
	 * validate the account
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validateAccount(array $postArray = []) : array
	{
		$nameValidator = new Validator\Name();
		$emailValidator = new Validator\Email();
		$userValidator = new Validator\User();
		$passwordValidator = new Validator\Password();
		$validateArray = [];

		/* validate post */

		if (!$postArray['adminName'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		else if (!$nameValidator->validate($postArray['adminName']))
		{
			$validateArray[] = $this->_language->get('name_incorrect');
		}
		if (!$postArray['adminUser'])
		{
			$validateArray[] = $this->_language->get('user_empty');
		}
		else if (!$userValidator->validate($postArray['adminUser']))
		{
			$validateArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['adminPassword'])
		{
			$validateArray[] = $this->_language->get('password_empty');
		}
		else if (!$passwordValidator->validate($postArray['adminPassword']))
		{
			$validateArray[] = $this->_language->get('password_incorrect');
		}
		if (!$postArray['adminEmail'])
		{
			$validateArray[] = $this->_language->get('email_empty');
		}
		else if (!$emailValidator->validate($postArray['adminEmail']))
		{
			$validateArray[] = $this->_language->get('email_incorrect');
		}
		return $validateArray;
	}

	/**
	 * touch sqlite file
	 *
	 * @since 3.0.0
	 *
	 * @param array $configArray
	 *
	 * @return bool
	 */

	protected function _touch(array $configArray = []) : bool
	{
		if ($configArray['dbType'] === 'sqlite')
		{
			$file = $configArray['dbHost'] . '.tmp';
			return touch($file) && unlink($file);
		}
		return true;
	}

	/**
	 * write config file
	 *
	 * @since 3.0.0
	 *
	 * @param array $configArray
	 *
	 * @return bool
	 */

	protected function _write(array $configArray = []) : bool
	{
		$this->_config->set('dbType', $configArray['dbType']);
		$this->_config->set('dbHost', $configArray['dbHost']);
		$this->_config->set('dbName', $configArray['dbName']);
		$this->_config->set('dbUser', $configArray['dbUser']);
		$this->_config->set('dbPassword', $configArray['dbPassword']);
		$this->_config->set('dbPrefix', $configArray['dbPrefix']);
		return $this->_config->write();
	}

	/**
	 * get the status
	 *
	 * @since 3.0.0
	 *
	 * @return int
	 */

	protected function _getStatus() : int
	{
		return Db::getStatus();
	}

	/**
	 * refresh the connection
	 *
	 * @since 3.0.0
	 */

	protected function _refreshConnection() : void
	{
		Db::resetDb();
		Db::init();
	}

	/**
	 * install the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $installArray
	 *
	 * @return bool
	 */

	protected function _install(array $installArray = []) : bool
	{
		$adminName = $installArray['adminName'];
		$adminUser = $installArray['adminUser'];
		$adminPassword = $installArray['adminPassword'];
		$adminEmail = $installArray['adminEmail'];
		if ($adminName && $adminUser && $adminPassword && $adminEmail)
		{
			$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
			$installer->init();
			$installer->rawDrop();
			$installer->rawCreate();
			$installer->insertData(
			[
				'adminName' => $installArray['adminName'],
				'adminUser' => $installArray['adminUser'],
				'adminPassword' => $installArray['adminPassword'],
				'adminEmail' => $installArray['adminEmail']
			]);
			return $this->_getStatus() === 2;
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
	 * @return bool
	 */

	protected function _mail(array $mailArray = []) : bool
	{
		$settingModel = new Model\Setting();
		$urlLogin = $this->_registry->get('root') . '/' . $this->_registry->get('parameterRoute') . 'login';

		/* html element */

		$linkElement = new Html\Element();
		$linkElement
			->init('a',
			[
				'href' => $urlLogin
			])
			->text($urlLogin);

		/* prepare mail */

		$toArray =
		[
			$mailArray['adminName'] => $mailArray['adminEmail']
		];
		$fromArray =
		[
			$settingModel->get('author') => $settingModel->get('email')
		];
		$subject = $this->_language->get('installation');
		$bodyArray =
		[
			$this->_language->get('user') . $this->_language->get('colon') . ' ' . $mailArray['adminUser'],
			'<br />',
			$this->_language->get('login') . $this->_language->get('colon') . ' ' . $linkElement
		];

		/* send mail */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $bodyArray);
		return $mailer->send();
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

	protected function _success(array $successArray = []) : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger
			->setUrl($this->_language->get('home'), $successArray['url'])
			->doRedirect()
			->success($successArray['message'], $successArray['title']);
	}

	/**
	 * show the warning
	 *
	 * @since 3.0.0
	 *
	 * @param array $warningArray array of the warning
	 *
	 * @return string
	 */

	protected function _warning(array $warningArray = []) : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger
			->setUrl($this->_language->get('home'), $warningArray['url'])
			->doRedirect()
			->warning($warningArray['message'], $warningArray['title']);
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

	protected function _error(array $errorArray = []) : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger
			->setUrl($this->_language->get('back'), $errorArray['url'])
			->error($errorArray['message'], $errorArray['title']);
	}
}
