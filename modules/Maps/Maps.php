<?php
namespace Redaxscript\Modules\Maps;

use Redaxscript\Head;
use Redaxscript\Html;

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

	protected static $_moduleArray =
	[
		'name' => 'Maps',
		'alias' => 'Maps',
		'author' => 'Redaxmedia',
		'description' => 'Integrate Google Maps',
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		if (!$this->_registry->get('adminParameter'))
		{
			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/Maps/dist/styles/maps.css');

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile($this->_configArray['apiUrl'] . '?key=' . $this->_configArray['apiKey'])
				->appendFile('modules/Maps/assets/scripts/init.js')
				->appendFile('modules/Maps/assets/scripts/maps.js');
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

	public function render($lat = 0, $lng = 0, $zoom = 1)
	{
		$mapElement = new Html\Element();
		$mapElement->init('div',
		[
			'class' => $this->_configArray['className'],
			'data-lat' => is_numeric($lat) ? $lat : null,
			'data-lng' => is_numeric($lng) ? $lng : null,
			'data-zoom' => is_numeric($zoom) ? $zoom : null
		]);

		/* collect output */

		$output = $mapElement;
		return $output;
	}
}
