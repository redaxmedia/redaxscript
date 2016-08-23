<?php
namespace Redaxscript\Modules\Tinymce;

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

class Tinymce extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Tinymce',
		'alias' => 'Tinymce',
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
		global $loader_modules_scripts;
		$loader_modules_scripts[] = 'modules/Tinymce/assets/scripts/init.js';
		$loader_modules_scripts[] = 'modules/Tinymce/assets/scripts/tinymce.js';
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
			$output = '<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.4.1/tinymce.min.js"></script>';
			echo $output;
		}
	}
}
