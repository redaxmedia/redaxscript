<?php
namespace Redaxscript\Modules\FeedReader;

use Redaxscript\Module;

/**
 * parent class to store module config
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
	 * module config
	 *
	 * @var array
	 */

	protected static $_config = array(
		'className' => array(
			'title' => 'title_feed_reader clearfix',
			'titleFirst' => 'title_first',
			'titleSecond' => 'title_second',
			'box' => 'box_feed_reader'
		)
	);
}