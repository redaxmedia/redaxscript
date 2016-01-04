<?php
namespace Redaxscript\Modules\Dawanda;

use Redaxscript\Module;

/**
 * javascript powered dawanda api client
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Dawanda extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Dawanda',
		'alias' => 'Dawanda',
		'author' => 'Redaxmedia',
		'description' => 'Javascript powered Dawanda API client',
		'version' => '2.6.2'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_scripts;
		$loader_modules_scripts[] = 'modules/Dawanda/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Dawanda/assets/scripts/dawanda.js';
	}
}
