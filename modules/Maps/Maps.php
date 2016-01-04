<?php
namespace Redaxscript\Modules\Maps;

use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * integrate social buttons
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Maps extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Maps',
		'alias' => 'Maps',
		'author' => 'Redaxmedia',
		'description' => 'Integrate Google Maps',
		'version' => '2.6.2'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		if (!Registry::get('adminParameter'))
		{
			global $loader_modules_styles, $loader_modules_scripts;
			$loader_modules_styles[] = 'modules/Maps/assets/styles/maps.css';
			$loader_modules_scripts[] = 'modules/Maps/assets/scripts/init.js';
			$loader_modules_scripts[] = 'modules/Maps/assets/scripts/maps.js';
		}
	}

	/**
	 * scriptEnd
	 *
	 * @since 2.2.0
	 */

	public static function scriptEnd()
	{
		if (!Registry::get('adminParameter'))
		{
			$output = '<script src="' . self::$_config['apiUrl'] . '?key=' . self::$_config['apiKey'] . '&amp;sensor=' . self::$_config['sensor'] . '"></script>';
			echo $output;
		}
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 *
	 * @param integer $lat
	 * @param integer $lng
	 * @param integer $zoom
	 *
	 * @return string
	 */

	public static function render($lat = 0, $lng = 0, $zoom = 1)
	{
		$mapElement = new Html\Element();
		$mapElement->init('div', array(
			'class' => self::$_config['className'],
			'data-lat' => is_numeric($lat) ? $lat : null,
			'data-lng' => is_numeric($lng) ? $lng : null,
			'data-zoom' => is_numeric($zoom) ? $zoom : null
		));

		/* collect output */

		$output = $mapElement;
		return $output;
	}
}
