<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Dater;
use Redaxscript\Model;
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
		$dater = new Dater();
		$dater->init();
		$this->_registry->set('now', $dater->getDateTime()->getTimeStamp());
		$this->_registry->set('cronUpdate', false);

		/* set the update */

		if (!$this->_request->getSession('nextUpdate'))
		{
			$this->_request->setSession('nextUpdate', $dater->getDateTime()->modify('+1 minute')->getTimeStamp());
			$this->_registry->set('cronUpdate', true);
		}

		/* reset the update */

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
				$categoryModel = new Model\Category();
				$articleModel = new Model\Article();
				$commentModel = new Model\Comment();
				$extraModel = new Model\Extra();
				$now = $this->_registry->get('now');

				/* publish by date */

				$categoryModel->publishByDate($now);
				$articleModel->publishByDate($now);
				$commentModel->publishByDate($now);
				$extraModel->publishByDate($now);
			}
		}
	}
}
