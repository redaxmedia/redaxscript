<?php
namespace Redaxscript\Modules\Ckeditor;

use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * javascript powered wysiwyg editor
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Ckeditor extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = [
		'name' => 'Ckeditor',
		'alias' => 'Ckeditor',
		'author' => 'Redaxmedia',
		'description' => 'Javascript powered WYSIWYG editor',
		'version' => '3.0.0'
	];

	/**
	 * loaderStart
	 *
	 * @since 3.0.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_scripts[] = 'modules/Ckeditor/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Ckeditor/assets/scripts/ckeditor.js';
	}

	/**
	 * scriptEnd
	 *
	 * @since 3.0.0
	 */

	public static function scriptEnd()
	{
		if (Registry::get('loggedIn') === Registry::get('token'))
		{
			$output = '<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script>';
			echo $output;
		}
	}
}
