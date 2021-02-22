<?php
namespace Redaxscript\Modules\Analytics;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * integrate google analytics
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Analytics extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Analytics',
		'alias' => 'Analytics',
		'author' => 'Redaxmedia',
		'description' => 'Integrate Google Analytics',
		'version' => '5.0.0',
		'license' => 'MIT'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('loggedIn') !== $this->_registry->get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					'https://google-analytics.com/analytics.js',
					'modules/Analytics/assets/scripts/init.js',
					'modules/Analytics/dist/scripts/analytics.min.js'
				]);
		}
	}
}
