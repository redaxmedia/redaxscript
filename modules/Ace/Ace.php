<?php
namespace Redaxscript\Modules\Ace;

use Redaxscript\Module;
use Redaxscript\Head;
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

	protected static $_moduleArray =
	[
		'name' => 'Ace',
		'alias' => 'Ace',
		'author' => 'Redaxmedia',
		'description' => 'Javascript powered code editor',
		'version' => '3.0.0'
	];

	/**
	 * loaderStart
	 *
	 * @since 2.6.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/Ace/assets/styles/ace.css';
		$loader_modules_scripts[] = 'modules/Ace/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Ace/assets/scripts/ace.js';
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
			$script = Head\Script::getInstance();
			$script->append(['src' => '//cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js']);
			echo $script;
		}
	}
}
