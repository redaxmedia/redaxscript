<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Module;

/**
 * children class to boot the cronjob
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Cronjob extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	protected function _autorun()
	{
		$this->_registry->set('now', date('Y-m-d H:i:s'));
		$this->_registry->set('cronUpdate', false);

		/* set update */

		if (!$this->_request->getSession('nextUpdate'))
		{
			$nextUpdate = date('Y-m-d H:i:s', strtotime('+1 minute'));
			$this->_request->setSession('nextUpdate', $nextUpdate);
			$this->_registry->set('cronUpdate', true);
		}

		/* reset update */

		if ($this->_request->getSession('nextUpdate') < $this->_registry->get('now'))
		{
			$this->_request->setSession('nextUpdate', false);
		}

		/* trigger update */

		if ($this->_registry->get('cronUpdate'))
		{
			Module\Hook::trigger('cronUpdate');
			if ($this->_registry->get('dbStatus') === 2)
			{
				future_update('categories');
				future_update('articles');
				future_update('comments');
				future_update('extras');
			}
		}
	}
}
