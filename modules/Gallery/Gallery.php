<?php
namespace Redaxscript\Modules\Gallery;

use Redaxscript\Html;

/**
 * read external rss and atom feeds
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Gallery extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Gallery',
		'alias' => 'Gallery',
		'author' => 'Redaxmedia',
		'description' => 'Lightbox enhanced image gallery',
		'version' => '2.6.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.6.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/gallery/styles/gallery.css';
		$loader_modules_styles[] = 'modules/gallery/styles/query.css';
		$loader_modules_scripts[] = 'modules/gallery/scripts/init.js';
		$loader_modules_scripts[] = 'modules/gallery/scripts/gallery.js';
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 *
	 * @param string $directory
	 * @param array $options
	 *
	 * @return string
	 */

	public static function render($directory = null, $options = array())
	{
	}

	/**
	 * createThumb
	 *
	 * @since 2.6.0
	 *
	 * @param string $fileName
	 * @param string $directory
	 * @param array $options
	 *
	 * @return string
	 */

	public static function _createThumb($fileName = null, $directory = null, $options = array())
	{
	}
}
