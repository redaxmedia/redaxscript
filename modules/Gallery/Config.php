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
		'height' => 100,
		'quality' => 80
	);
}