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
	 * array of the config
	 *
	 * @var array
	 */

	private static $_configArray = array();

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $file file with config
	 */

	public static function init($file = 'config.php')
	{
		if (file_exists($file))
		{
			$contents = file_get_contents($file);
			$_configArray = json_decode($contents, true);
			if (is_array($_configArray))
			{
				self::$_configArray = array_merge(self::$_configArray, $_configArray);
			}
		}
	}

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
			$output = self::$_configArray;
		}
		else if (array_key_exists($key, self::$_configArray))
		{
			$output = self::$_configArray[$key];
		}
		return $output;
	}

	/**
	 * set item to config
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 */

	public static function set($key = null, $value = null)
	{
		self::$_configArray[$key] = $value;
	}
}
