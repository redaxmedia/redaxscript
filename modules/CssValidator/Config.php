<?php
namespace Redaxscript\Modules\CssValidator;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 4.0.0
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
		'apiUrl' => 'http://jigsaw.w3.org/css-validator/validator',
		'profile' => 'css3svg'
	];
}
