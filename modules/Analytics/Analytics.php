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
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Analytics',
		'alias' => 'Analytics',
		'author' => 'Redaxmedia',
		'description' => 'Integrate Google Analytics',
		'version' => '2.4.0',
		'status' => 1,
		'access' => 0
	);

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
			$loader_modules_scripts[] = 'modules/Analytics/scripts/init.js';
			$loader_modules_scripts[] = 'modules/Analytics/scripts/analytics.js';
		}
	}

	/**
	 * scriptsStart
	 *
	 * @since 2.2.0
	 */

	public static function scriptsStart()
	{
		if (Registry::get('loggedIn') !== Registry::get('token'))
		{
			$output = '<script src="//google-analytics.com/ga.js"></script>';
			echo $output;
		}
	}
}
