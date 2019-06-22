<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Filter;

/**
 * children class to boot the cache
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Cache extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	public function autorun() : void
	{
		$filterBoolean = new Filter\Boolean();
		$noCache = $filterBoolean->sanitize($this->_request->getQuery('no-cache'));

		/* set the registry */

		$this->_registry->set('noAssetCache', false);
		$this->_registry->set('noPageCache', false);
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token') || $noCache)
		{
			$this->_registry->set('noAssetCache', true);
			$this->_registry->set('noPageCache', true);
		}
	}
}
