<?php
namespace Redaxscript\Modules\Ace;

use Redaxscript\Module;
use Redaxscript\Head;

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
		'description' => 'JavaScript powered code editor',
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token'))
		{
			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/Ace/dist/styles/ace.min.css');

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('//cdnjs.cloudflare.com/ajax/libs/ace/1.2.6/ace.js')
				->appendFile('modules/Ace/assets/scripts/init.js')
				->appendFile('modules/Ace/dist/scripts/ace.min.js');
		}
	}
}
