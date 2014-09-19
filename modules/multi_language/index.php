<?php
namespace Redaxscript\Modules;

use Redaxscript\Module;

/**
 * support for multiple languages
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class MultiLanguage extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'MultiLanguage',
		'alias' => 'multi_language',
		'author' => 'Redaxmedia',
		'description' => 'Support for multiple languages',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/multi_language/styles/multi_language.css';
	}
}