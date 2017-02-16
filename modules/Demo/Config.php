<?php
namespace Redaxscript\Modules\Demo;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module\Module
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'modules' =>
		[
			'Analytics' => 'Redaxscript\Modules\Analytics\Analytics',
			'Demo' => 'Redaxscript\Modules\Demo\Demo',
			'Editor' => 'Redaxscript\Modules\Editor\Editor'
		]
	];
}