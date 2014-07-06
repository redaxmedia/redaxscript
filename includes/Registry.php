<?php

/**
 * parent class to store the registry
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Registry
 * @author Gary Aylward
 */

class Redaxscript_Registry extends Redaxscript_Singleton
{
	/**
	 * array of registry values
	 *
	 * @var array
	 */

	protected static $_values = array();

	/**
	 * init the class
	 *
	 * @since 2.1.0
	 *
	 * @param array $values array of registry values
	 */

	public function init($values = array())
	{
		if (is_array($values))
		{
			self::$_values = $values;
		}
	}

	/**
	 * get item from registry
	 *
	 * @since 2.1.0
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
			$output = self::$_values;
		}
		else if (array_key_exists($key, self::$_values))
		{
			$output = self::$_values[$key];
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
		self::$_values[$key] = $value;
	}
}
