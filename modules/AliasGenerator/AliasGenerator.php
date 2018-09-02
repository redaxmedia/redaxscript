<?php
namespace Redaxscript\Modules\AliasGenerator;

use Redaxscript\Module;
use Redaxscript\Head;

/**
 * javascript powered alias generator
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
		'description' => 'JavaScript powered alias generator',
		'version' => '4.0.0',
		'access' => '1'
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('https://cdnjs.cloudflare.com/ajax/libs/speakingurl/14.0.1/speakingurl.min.js')
				->appendFile('modules/AliasGenerator/assets/scripts/init.js')
				->appendFile('modules/AliasGenerator/dist/scripts/alias-generator.min.js');
		}
	}
}
