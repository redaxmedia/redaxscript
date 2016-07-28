<?php
namespace Redaxscript\Modules\Maps;

use Redaxscript\Module;

/**
 * children class to store module config
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected static $_configArray =
	[
		'className' => 'rs-js-map rs-map',
		'apiUrl' => 'https://maps.googleapis.com/maps/api/js',
		'apiKey' => 'AIzaSyApJDayHOmOnVy6OucJXG_cGSHSC_f7NSM',
		'sensor' => true
	];
}
