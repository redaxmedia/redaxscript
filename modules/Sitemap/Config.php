<?php
namespace Redaxscript\Modules\Sitemap;

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
			'title' => 'title_content_sub title_sitemap',
			'list' => 'list_default list_sitemap'
		)
	);
}