<?php
namespace Redaxscript\Modules\Gallery;

use Redaxscript\Module;

/**
 * children class to store module config
 *
 * @since 2.6.0
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
			'image' => 'rs-image-gallery',
			'list' => 'rs-js-gallery rs-list-gallery'
		),
		'height' => 200,
		'quality' => 80,
		'thumbDirectory' => 'thumbs',
		'allowedCommands' => array(
			'create',
			'remove'
		)
	);
}
