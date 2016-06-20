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

class Config extends Module
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected static $_configArray = array(
		'className' => array(
			'title' => 'rs-title-feed-reader rs-is-clearfix',
			'box' => 'rs-box-feed-reader'
		)
	);
}