<?php
namespace Redaxscript\Modules\Demo;

use Redaxscript\Module;

/**
 * children class to store module config
 *
 * @since 2.4.0
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
		'modules' => array(
			'Analytics' => 'Redaxscript\Modules\Analytics\Analytics',
			'Demo' => 'Redaxscript\Modules\Demo\Demo',
			'Editor' => 'Redaxscript\Modules\Editor\Editor'
		)
	);
}