<?php
namespace Redaxscript\Modules;

use Redaxscript\Module;

/**
 * live reload for css
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class LiveReload extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Live Reload',
		'alias' => 'live_reload',
		'author' => 'Redaxmedia',
		'description' => 'Live reload for CSS',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 1
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/live_reload/styles/live_reload.css';
		$loader_modules_scripts[] = 'modules/live_reload/scripts/startup.js';
		$loader_modules_scripts[] = 'modules/live_reload/scripts/live_reload.js';
	}
}