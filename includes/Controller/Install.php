<?php
namespace Redaxscript\Controller;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Html;
use Redaxscript\Mailer;
use Redaxscript\Messenger;
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
			'd_type' => $specialFilter->sanitize($this->_request->getPost('db-type')),
			'd_host' => $specialFilter->sanitize($this->_request->getPost('db-host')),
			'd_name' => $specialFilter->sanitize($this->_request->getPost('db-name')),
			'd_user' => $specialFilter->sanitize($this->_request->getPost('db-user')),
			'd_password' => $specialFilter->sanitize($this->_request->getPost('db-password')),
			'd_prefix' => $specialFilter->sanitize($this->_request->getPost('db-prefix')),
			'd_salt' => $specialFilter->sanitize($this->_request->getPost('db-salt')),
			'name' => $specialFilter->sanitize($this->_request->getPost('admin-name')),
			'user' => $specialFilter->sanitize($this->_request->getPost('admin-user')),
			'password' => $specialFilter->sanitize($this->_request->getPost('admin-password')),
			'email' => $emailFilter->sanitize($this->_request->getPost('admin-email'))
		);

		$postArray = $this->_checkPost($postArray);

		/* handle error */

		if (!($errorArray = !$this->_validate($postArray)))
		{
			return $this->_error($errorArray);
		}

		$this->_mail($postArray);

		return $this->_write($postArray);
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _success()
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_registry->get('root'))->success($this->_language->get('installation_completed'));
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
		$messenger = new Messenger();
		return $messenger->error($errorArray, $this->_language->get('alert'));
	}

	/**
	 * check the postArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray array to be validated
	 *
	 * @return array
	 */


	protected function _checkPost($postArray = array())
	{
		if (!$postArray['d_type'])
		{
			$postArray['d_type'] = 'mysql';
		}
		if (!$postArray['d_host'])
		{
			$postArray['d_host'] = 'localhost';
		}
		if (!$postArray['user'])
		{
			$postArray['user'] = 'admin';
		}
		if (!$postArray['password'])
		{
			$postArray['password'] = uniqid();
		}

		return $postArray;
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

		if ($postArray['d_type'] != 'sqlite' && !$postArray['name'])
		{
			$errorArray[] = $this->_language->get('name_empty');
		}
		else if ($postArray['d_type'] != 'sqlite' && !$postArray['user'])
		{
			$errorArray[] = $this->_language->get('user_empty');
		}
		else if ($postArray['d_type'] != 'sqlite' && !$postArray['password'])
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
	 * write config
	 *
	 * @since 3.0.0
	 *
	 * @param array $writeArray
	 *
	 * @return array
	 */

	protected function _write($writeArray = array())
	{
		$config = Config::getInstance();
		$config->set('dbType', $writeArray['d_type']);
		$config->set('dbHost', $writeArray['d_host']);
		$config->set('dbName', $writeArray['d_name']);
		$config->set('dbUser', $writeArray['d_user']);
		$config->set('dbPassword', $writeArray['d_password']);
		$config->set('dbPrefix', $writeArray['d_prefix']);
		$config->set('dbSalt', $writeArray['d_salt']);
		$config->write();
	}

	/**
	 * write config
	 *
	 * @since 3.0.0
	 *
	 * @param $postArray
	 *
	 * @return array
	 */

	protected function _check($postArray)
	{
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		if ($_POST['Redaxscript\View\InstallForm'] && $this->_registry->get('dbStatus') && $postArray['name']
			&& $loginValidator->validate($postArray['user']) == Validator\ValidatorInterface::PASSED
			&& $loginValidator->validate($postArray['password']) == Validator\ValidatorInterface::PASSED
			&& $emailValidator->validate($postArray['email']) == Validator\ValidatorInterface::PASSED)
		{
			return 1;
		} else
		{
			return 0;
		}
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

	private function _mail($mailArray)
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
}