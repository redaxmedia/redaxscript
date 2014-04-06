<?php

/**
 * The Registry class provides storage for constants
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Registry
 * @author Gary Aylward
 */

class Redaxscript_Registry
{
	/**
	 * array of registry values
	 *
	 * @var array
	 */

	protected static $_values = array();

	/**
	 * singleton instance of the class
	 *
	 * @var object
	 */

	protected static $_instance = null;

	/**
	 * constructor is private to ensure singleton
	 *
	 * @since 2.1.0
	 */

	private function __construct()
	{
	}

	/**
	 * fill values array with data
	 *
	 * @since 2.1.0
	 *
	 * @param array $values An associative array of names & values to store
	 */

	public function init($values = array())
	{
		if (is_array($values))
		{
			self::$_values = $values;
		}
	}

	/**
	 * get item from values array
	 *
	 * @since 2.1.0
	 *
	 * @param string $key The name of the item to get
	 * @return string
	 */

	public static function get($key = null)
	{
		if (array_key_exists($key, self::$_values))
		{
			$output = self::$_values[$key];
		}
		else
		{
			$output = null;
		}
		return $output;
	}

	/**
	 * set item to values array
	 *
	 * @since 2.1.0
	 *
	 * @param string $key The name of the item to set
	 * @param mixed $value The value of the item
	 */

	public static function set($key = null, $value = null)
	{
		self::$_values[$key] = $value;
	}

	/**
	 * create and return the instance of the singleton class
	 *
	 * @since 2.1.0
	 *
	 * @return object
	 */

	public static function instance()
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}
}
?>