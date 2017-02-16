<?php
namespace Redaxscript\Modules\Disqus;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 2.2.0
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
		'id' => 'disqus',
		'url' => '//example.disqus.com/embed.js'
	];
}
