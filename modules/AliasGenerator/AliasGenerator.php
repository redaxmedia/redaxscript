<?php
namespace Redaxscript\Modules\AliasGenerator;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * generate user friendly url fragments
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class AliasGenerator extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Alias Generator',
		'alias' => 'AliasGenerator',
		'author' => 'Redaxmedia',
		'description' => 'Generate user friendly URL fragments',
		'version' => '4.4.0'
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					'https://cdnjs.cloudflare.com/ajax/libs/speakingurl/14.0.1/speakingurl.min.js',
					'modules/AliasGenerator/assets/scripts/init.js',
					'modules/AliasGenerator/dist/scripts/alias-generator.min.js'
				]);
		}
	}
}
