<?php
namespace Redaxscript\Modules\Analytics;

use Redaxscript\Module;
use Redaxscript\Head;
use Redaxscript\Registry;

/**
 * integrate google analytics
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Analytics extends Module
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
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public static function renderStart()
	{
		if (Registry::get('loggedIn') !== Registry::get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('//google-analytics.com/analytics.js')
				->appendFile('modules/Analytics/assets/scripts/init.js')
				->appendFile('modules/Analytics/assets/scripts/analytics.js');
		}
	}
}
