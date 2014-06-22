<?php

/**
 * parent class to store database config
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Config
 * @author Henry Ruhs
 */

class Redaxscript_Config extends Redaxscript_Singleton
{
	/**
	 * database config
	 *
	 * @var array
	 */

	private static $_config = array(
		// [config]
		'type' => 'mysql',
		'host' => 'redaxscript.com',
		'name' => 'd01ae38a',
		'user' => 'd01ae38a',
		'password' => 'travis',
		'prefix' => '',
		'salt' => ''
		// [/config]
	);

	/**
	 * get item from config
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array
	 */

	public static function get($key = null)
	{
		$output = null;

		/* values as needed */

		if (is_null($key))
		{
			$output = self::$_config;
		}
		else if (array_key_exists($key, self::$_config))
		{
			$output = self::$_config[$key];
		}
		return $output;
	}
}
