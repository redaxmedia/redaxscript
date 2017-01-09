<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Module;

/**
 * children class to store module config
 *
 * @since 2.3.0
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
			'title' => 'rs-title-feed-reader rs-fn-clearfix',
			'box' => 'rs-box-feed-reader'
		]
	];
}