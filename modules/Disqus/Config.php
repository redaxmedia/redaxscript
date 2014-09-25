<?php
namespace Redaxscript\Modules\Disqus;

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
		'element' => '<div id="disqus_thread"></div>',
		'url' => '//example.disqus.com/embed.js'
	);
}