<?php
namespace Redaxscript\Modules\Archive;

use Redaxscript\Module;

/**
 * generate a archive tree
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Archive extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Archive',
		'alias' => 'Archive',
		'author' => 'Redaxmedia',
		'description' => 'Generate a archive tree',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * render the breadcrumb trail as an unordered list
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */

	public static function render()
	{

	}

	/**
	 * build the archive array
	 *
	 * @since 2.2.0
	 */

	private static function _build()
	{

	}
}