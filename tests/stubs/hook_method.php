<?php
namespace Redaxscript\Modules;

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

class CallHome
{
	public static function hookMethod($first = null, $second = null)
	{
		return $first - $second;
	}
}