<?php
namespace Redaxscript\Modules\Ace;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * javascript powered html editor
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Ace extends Module\Module
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
		'description' => 'JavaScript powered HTML editor',
		'version' => '4.3.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
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
				->appendFile(
				[
					'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.7/ace.js',
					'modules/Ace/assets/scripts/init.js',
					'modules/Ace/dist/scripts/ace.min.js'
				]);
		}
	}
}
