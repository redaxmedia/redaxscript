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

	protected static $_registryArray = [];

	/**
	 * init the class
	 *
	 * @since 2.1.0
	 *
	 * @param array $registryArray array of the registry
	 */

	public function init($registryArray = [])
	{
		if (is_array($registryArray))
		{
			self::$_registryArray = array_merge(self::$_registryArray, $registryArray);
		}
	}

	/**
	 * get item from registry
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return mixed
	 */

	public function get($key = null)
	{
		if (array_key_exists($key, self::$_registryArray))
		{
			return self::$_registryArray[$key];
		}
		else if (!$key)
		{
			return self::$_registryArray;
		}
		return false;
	}

	/**
	 * set item to registry
	 *
	 * @since 2.1.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function set($key = null, $value = null)
	{
		self::$_registryArray[$key] = $value;
	}
}
