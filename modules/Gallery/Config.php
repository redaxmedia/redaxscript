<?php
namespace Redaxscript\Modules\Gallery;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module\Notification
{
	/**
	 * array of the config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'className' =>
		[
			'image' => 'rs-image-gallery',
			'list' => 'rs-js-gallery rs-list-gallery'
		],
		'allowedCommands' =>
		[
			'create',
			'remove'
		],
		'height' => 200,
		'quality' => 80,
		'thumbDirectory' => 'thumbs'
	];
}
