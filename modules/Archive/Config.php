<?php
namespace Redaxscript\Modules\Archive;

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
			'headline' => 'title_content_sub title_archive',
			'list' => 'list_default list_archive'
		)
	);
}
