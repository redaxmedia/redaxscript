<?php
namespace Redaxscript\Modules\LazyLoad;

use Redaxscript\Module;

/**
 * parent class to store module config
 *
 * @since 2.2.0
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
			'image' => 'js_lazy_load lazy_load',
			'placeholder' => 'placeholder_lazy_load'
		),
		'placeholder' => 'modules/LazyLoad/images/placeholder.png',
		'device' => array(
			'desktop',
			'tablet',
			'mobile'
		)
	);
}