<?php
namespace Redaxscript\Modules;

use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * provide version and news updates
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CallHome extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Call Home',
		'alias' => 'call_home',
		'author' => 'Redaxmedia',
		'description' => 'Provide version and news updates',
		'version' => '2.2.0',
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
		global $loader_modules_scripts;
		$loader_modules_scripts[] = 'modules/call_home/scripts/startup.js';
		$loader_modules_scripts[] = 'modules/call_home/scripts/call_home.js';
	}

	/**
	 * scriptsStart
	 *
	 * @since 2.2.0
	 */

	public static function scriptsStart()
	{
		if (Registry::get('loggedIn') === Registry::get('token') && Registry::get('firstParameter') === 'admin')
		{
			$output = '<script src="//google-analytics.com/ga.js"></script>' . PHP_EOL;
			echo $output;
		}
	}

	/**
	 * adminNotificationStart
	 *
	 * @since 2.2.0
	 */

	public static function adminNotificationStart()
	{
		$url = 'http://service.redaxscript.com/version/' . l('redaxscript_version');
		$contents = file_get_contents($url);

		/* collect output */

		if ($contents)
		{
			$output = $contents;
		}
		echo $output;
	}

	/**
	 * adminNotificationEnd
	 *
	 * @since 2.2.0
	 */

	public static function adminNotificationEnd()
	{
		$url = 'http://service.redaxscript.com/news';
		$contents = file_get_contents($url);

		/* collect output */

		if ($contents)
		{
			$output = $contents;
		}
		echo $output;
	}
}