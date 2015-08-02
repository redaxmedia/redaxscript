<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Directory;
use Redaxscript\Module;
use Redaxscript\Element;

/**
 * simple directory lister
 *
 * @since 2.5.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class DirectoryLister extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Directory lister',
		'alias' => 'DirectoryLister',
		'author' => 'Redaxmedia',
		'description' => 'Simple directory lister',
		'version' => '2.5.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.5.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/DirectoryLister/styles/diretory_lister.css';
	}

	/**
	 * render
	 *
	 * @since 2.5.0
	 *
	 * @param string $directory
	 *
	 * @return string
	 */

	public static function render($directory = null)
	{
		$output = '';
		if ($directory)
		{
		}
		return $output;
	}
}
