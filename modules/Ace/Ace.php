<?php
namespace Redaxscript\Modules\Ace;

use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * javascript powered code editor
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Ace extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Ace',
		'alias' => 'Ace',
		'author' => 'Redaxmedia',
		'description' => 'Javascript powered code editor',
		'version' => '2.6.2'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.6.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/Ace/styles/ace.css';
		$loader_modules_scripts[] = 'modules/Ace/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Ace/scripts/ace.js';
	}

	/**
	 * scriptEnd
	 *
	 * @since 2.6.0
	 */

	public static function scriptEnd()
	{
		if (Registry::get('loggedIn') === Registry::get('token'))
		{
			$output = '<script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js"></script>';
			echo $output;
		}
	}
}
