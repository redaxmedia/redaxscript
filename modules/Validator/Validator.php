<?php
namespace Redaxscript\Modules\Validator;

use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * html validator for developers
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Validator extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Validator',
		'alias' => 'Validator',
		'author' => 'Redaxmedia',
		'description' => 'HTML validator for developers',
		'version' => '2.5.0',
		'access' => '1'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		if (Registry::get('firstParameter') !== 'admin')
		{
			global $loader_modules_styles, $loader_modules_scripts;
			$loader_modules_styles[] = 'modules/Validator/styles/validator.css';
			$loader_modules_scripts[] = 'modules/Validator/scripts/init.js';
			$loader_modules_scripts[] = 'modules/Validator/scripts/validator.js';
		}
	}
}
