<?php
namespace Redaxscript\Modules\Maps;

use Redaxscript\Module;

/**
 * parent class to store module config
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
	 * module config
	 *
	 * @var array
	 */

	protected static $_config = array(
		'className' => 'js_map map',
		'apiUrl' => 'https://maps.googleapis.com/maps/api/js',
		'apiKey' => 'AIzaSyApJDayHOmOnVy6OucJXG_cGSHSC_f7NSM',
		'sensor' => true
	);
}
