<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Auth;
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

	public function autorun() : void
	{
		$auth = new Auth($this->_request);

		/* set the registry */

		if ($auth->getStatus())
		{
			$this->_registry->set('loggedIn', $this->_registry->get('token'));
			$this->_registry->set('authStatus', $auth->getStatus());
		}
		$this->_registry->set('dbStatus', Db::getStatus());
	}
}
