<?php
namespace Redaxscript\Modules\PageCache;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module\Module
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'directory' => 'cache/pages',
		'extension' => 'phtml',
		'lifetime' => 86400,
		'tokenPlaceholder' => '%TOKEN%'
	];
}
