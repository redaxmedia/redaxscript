<?php
namespace Redaxscript\Modules\Disqus;

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
		'id' => 'disqus_thread',
		'url' => '//example.disqus.com/embed.js'
	);
}
