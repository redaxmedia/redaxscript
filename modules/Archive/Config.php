<?php
namespace Redaxscript\Modules\Archive;

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
	 * array of config
	 *
	 * @var array
	 */

	protected static $_configArray =
	[
		'className' =>
		[
			'title' => 'rs-title-content-sub rs-title-archive',
			'list' => 'rs-list-default rs-list-archive'
		]
	];
}
