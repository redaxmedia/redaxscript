<?php
namespace Redaxscript\Modules\Test;

use Redaxscript\Module;

/**
 * made for testing
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Test extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Test',
		'alias' => 'Test',
		'author' => 'Redaxmedia',
		'description' => 'Made for testing',
		'version' => '2.5.0'
	);

	/**
	 * render
	 *
	 * @since 2.4.0
	 *
	 * @param integer $first
	 * @param integer $second
	 *
	 * @return integer
	 */

	public static function render($first = null, $second = null)
	{
		return $first + $second;
	}
}
