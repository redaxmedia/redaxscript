<?php
namespace Redaxscript\Bootstrap;

use function session_id;
use function session_regenerate_id;
use function session_start;
use function session_status;

/**
 * children class to boot the session
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Session extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	public function autorun() : void
	{
		session_start();
		$this->_request->refreshSession();

		/* session guard */

		if (!$this->_request->getSession('sessionGuard'))
		{
			$this->_request->setSession('sessionGuard', session_regenerate_id());
		}
		$this->_registry->set('sessionId', session_id());
		$this->_registry->set('sessionStatus', session_status());
	}
}
