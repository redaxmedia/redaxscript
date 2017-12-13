<?php
namespace Redaxscript\Modules\LiveReload;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 3.3.0
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
		'url' => 'http://localhost:7000/livereload.js'
	];
}
