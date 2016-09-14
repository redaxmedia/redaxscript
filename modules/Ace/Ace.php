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
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public static function renderStart()
	{
		if (Registry::get('loggedIn') === Registry::get('token'))
		{
			/* link */

			$link = Head\Link::getInstance();
			$link->appendFile('modules/Ace/assets/styles/ace.css');

			/* script */

			$script = Head\Script::getInstance();
			$script->appendFile('modules/Ace/assets/scripts/init.js');
			$script->appendFile('modules/Ace/assets/scripts/ace.js');
			$script->appendFile('//cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js');
		}
	}
}

/*@todo: refactor ALL modules that using renderStart() hooks - remove the global variables and loaderStart hook
/*@todo: do not echo here - we just do the collecting - echo is job of the template */
