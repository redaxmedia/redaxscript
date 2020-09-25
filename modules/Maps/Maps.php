<?php
namespace Redaxscript\Modules\Maps;

use Redaxscript\Head;
use Redaxscript\Html;
use Redaxscript\Module;
use function http_build_query;
use function is_numeric;
use function urldecode;

/**
 * integrate google maps
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Maps extends Module\Module
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
		'version' => '4.5.0',
		'license' => 'MIT'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' => 'rs-js-map rs-component-map',
		'apiUrl' => 'https://maps.googleapis.com/maps/api/js',
		'apiKey' => null
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
	{
		if (!$this->_registry->get('adminParameter'))
		{
			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/Maps/dist/styles/maps.min.css');

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					$this->_optionArray['apiUrl'] . '?' . urldecode(http_build_query(
					[
						'key' => $this->_optionArray['apiKey']
					])),
					'modules/Maps/assets/scripts/init.js',
					'modules/Maps/dist/scripts/maps.min.js'
				]);
		}
	}

	/**
	 * render
	 *
	 * @since 3.0.0
	 *
	 * @param int $latitude
	 * @param int $longitude
	 * @param int $zoom
	 *
	 * @return string
	 */

	public function render(int $latitude = 0, int $longitude = 0, int $zoom = 1) : string
	{
		$mapElement = new Html\Element();
		$mapElement->init('div',
		[
			'class' => $this->_optionArray['className'],
			'data-latitude' => is_numeric($latitude) ? $latitude : 0,
			'data-longitude' => is_numeric($longitude) ? $longitude : 0,
			'data-zoom' => is_numeric($zoom) ? $zoom : 1
		]);

		/* collect output */

		$output = $mapElement;
		return $output;
	}
}
