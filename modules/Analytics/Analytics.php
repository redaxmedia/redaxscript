<?php
namespace Redaxscript\Modules\Analytics;

use Redaxscript\Module;
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
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		if (Registry::get('loggedIn') !== Registry::get('token'))
		{
			global $loader_modules_scripts;
			$loader_modules_scripts[] = 'modules/Analytics/assets/scripts/init.js';
			$loader_modules_scripts[] = 'modules/Analytics/assets/scripts/analytics.js';
		}
	}

	/**
	 * scriptStart
	 *
	 * @since 2.2.0
	 */

	public static function scriptStart()
	{
		if (Registry::get('loggedIn') !== Registry::get('token'))
		{
			$output = '<script src="//google-analytics.com/analytics.js"></script>';
			echo $output;
		}
	}
}
