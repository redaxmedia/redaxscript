<?php
namespace Redaxscript\Modules\Validator;

use Redaxscript\Module;

/**
 * children class to store module config
 *
 * @since 3.0.0
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

	protected static $_configArray = array(
		'className' => array(
			'text' => 'rs-admin-text-panel',
			'warning' => 'rs-admin-is-warning',
			'error' => 'rs-admin-is-error'
		),
		'url' => 'https://validator.nu/?doc=',
		'parser' => 'html5',
		'typeArray' => array(
			'warning',
			'error'
		)
	);
}