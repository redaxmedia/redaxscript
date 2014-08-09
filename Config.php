<?php
namespace Redaxscript;

/**
 * parent class to store database config
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Config
 * @author Henry Ruhs
 */

class Config extends Singleton
{
	/**
	 * database config
	 *
	 * @var array
	 */

	private static $_config = array(
		// @configStart
		'type' => '',
		'host' => '',
		'name' => '',
		'user' => '',
		'password' => '',
		'prefix' => '',
		'salt' => ''
		// @configEnd
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
		$output = false;

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

	/**
	 * set item to config
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function set($key = null, $value = null)
	{
		self::$_config[$key] = $value;
	}
}