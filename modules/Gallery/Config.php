<?php
namespace Redaxscript\Modules\Gallery;

use Redaxscript\Module;

/**
 * parent class to store module config
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
	 * module config
	 *
	 * @var array
	 */

	protected static $_config = array(
		'className' => array(
			'image' => 'image image_gallery',
			'list' => 'js_list_gallery list_gallery'
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