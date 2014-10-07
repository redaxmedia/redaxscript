<?php
namespace Redaxscript\Modules\CallHome;

/**
 * stub to test a module
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CallHome
{
	/**
	 * hookMethod
	 *
	 * @since 2.2.0
	 *
	 * @param integer $first
	 * @param integer $second
	 *
	 * @return integer
	 */

	public static function hookMethod($first = null, $second = null)
	{
		return $first - $second;
	}

	/**
	 * render
	 *
	 * @since 2.2.0
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