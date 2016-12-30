<?php
namespace Redaxscript\Modules\Sitemap;

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

class Config extends Module\Module
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'className' =>
		[
			'title' => 'rs-title-content-sub rs-title-sitemap',
			'list' => 'rs-list-default rs-list-sitemap'
		]
	];
}