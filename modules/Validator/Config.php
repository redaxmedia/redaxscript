<?php
namespace Redaxscript\Modules\Validator;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module\Notification
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'apiUrl' => 'https://validator.w3.org/nu/?doc=',
		'typeArray' =>
		[
			'warning',
			'error'
		]
	];
}