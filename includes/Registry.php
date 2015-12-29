<?php
namespace Redaxscript;

/**
 * children class to store the registry
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Registry
 * @author Gary Aylward
 */

class Registry extends Singleton
{
	/**
	 * array of the registry
	 *
	 * @var array
	 */

	protected static $_registryArray = array();

	/**
	 * init the class
	 *
	 * @since 2.1.0
	 *
	 * @param array $registryArray array of the registry
	 */

	public static function init($registryArray = array())
	{
		if (is_array($registryArray))
		{
			self::$_registryArray = $registryArray;
		}
	}

	/**
	 * get item from registry
	 *
	 * @since 2.1.0
	 *
	 * @param string $key key of the item
	 *
	 * @return mixed
	 */

	public static function get($key = null)
	{
		$output = false;

		/* values as needed */

		if (!$key)
		{
			$output = self::$_registryArray;
		}
		else if (array_key_exists($key, self::$_registryArray))
		{
			$output = self::$_registryArray[$key];
		}
		return $output;
	}

	/**
	 * set item to registry
	 *
	 * @since 2.1.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function set($key = null, $value = null)
	{
		self::$_registryArray[$key] = $value;
	}
}
