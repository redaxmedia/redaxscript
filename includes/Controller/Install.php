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