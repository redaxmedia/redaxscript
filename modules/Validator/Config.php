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

	protected static $_configArray =
	[
		'className' =>
		[
			'text' => 'rs-admin-text-panel',
			'code' => 'rs-admin-code-panel',
			'warning' => 'rs-admin-is-warning',
			'error' => 'rs-admin-is-error'
		],
		'url' => 'https://validator.w3.org/nu/?doc=',
		'typeArray' =>
		[
			'warning',
			'error'
		]
	];
}