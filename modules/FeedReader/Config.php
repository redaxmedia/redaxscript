<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Module;

/**
 * children class to store module configuration
 *
 * @since 2.3.0
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
		'className' =>
		[
			'title' => 'rs-title-content rs-title-feed-reader',
			'box' => 'rs-box-content rs-box-feed-reader'
		]
	];
}