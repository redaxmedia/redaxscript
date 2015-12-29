<?php
namespace Redaxscript\Modules\LazyLoad;

use Redaxscript\Module;

/**
 * children class to store module config
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
			'image' => 'rs-js-lazy-load rs-lazy-load',
			'placeholder' => 'rs-placeholder-lazy-load'
		),
		'placeholder' => 'modules/LazyLoad/images/placeholder.png',
		'device' => array(
			'desktop',
			'tablet',
			'mobile'
		)
	);
}