<?php
namespace Redaxscript\Modules\PageCache;

use Redaxscript\Module;

/**
 * children class to store module config
 *
 * @since 3.0.0
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

	protected static $_configArray =
	[
		'directory' => 'cache/pages',
		'extension' => 'phtml',
		'lifetime' => 86400,
		'tokenPlaceholder' => '%TOKEN%'
	];
}
