<?php
namespace Redaxscript\Modules\LightGallery;

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
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'className' =>
		[
			'image' => 'rs-image-light-gallery',
			'list' => 'rs-js-light-gallery rs-list-light-gallery rs-fn-clearfix'
		],
		'height' => 200,
		'quality' => 80,
		'thumbDirectory' => 'thumbs',
		'allowedCommands' =>
		[
			'create',
			'remove'
		]
	];
}
