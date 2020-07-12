<?php
namespace Redaxscript\Modules\FormValidator;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * validate using the constraint validation api
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class FormValidator extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Form Validator',
		'alias' => 'FormValidator',
		'author' => 'Redaxmedia',
		'description' => 'Validate using the constraint validation API',
		'version' => '4.3.1'
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart() : void
	{
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'modules/FormValidator/assets/scripts/init.js',
				'modules/FormValidator/dist/scripts/form-validator.min.js'
			]);
	}
}
