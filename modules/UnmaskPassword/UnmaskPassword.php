<?php
namespace Redaxscript\Modules\UnmaskPassword;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * unmask focused password fields
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class UnmaskPassword extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Unmask Password',
		'alias' => 'UnmaskPassword',
		'author' => 'Redaxmedia',
		'description' => 'Unmask focused password fields',
		'version' => '4.4.0'
	];

	/**
	 * renderStart
	 *
	 * @since 4.3.0
	 */

	public function renderStart() : void
	{
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'modules/UnmaskPassword/assets/scripts/init.js',
				'modules/UnmaskPassword/dist/scripts/unmask-password.min.js'
			]);
	}
}
