<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Auth as BaseAuth;
use Redaxscript\Db;

/**
 * children class to boot the status
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Status extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	protected function _autorun()
	{
		$auth = new BaseAuth($this->_request);

		/* set registry */

		if ($auth->getStatus())
		{
			$this->_registry->set('loggedIn', $this->_registry->get('token'));
			$this->_registry->set('authStatus', $auth->getStatus());
		}
		$this->_registry->set('dbStatus', Db::getStatus());
	}
}
