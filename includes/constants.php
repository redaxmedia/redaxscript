<?php

/**
 * constants
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Constants
 * @author Gary Aylward
 */

class Redaxscript_Constants
{

	protected static $_values = array();
	protected static $_instance = null;

	/**
	 * construct
	 *
	 * Constructor is private to ensure singleton
	 *
	 * @since 2.1.0
	 *
	 */

	private function __construct()
	{
	}

	/**
	 * setInstance
	 *
	 * Sets a reference to the instance of the singleton class
	 *
	 * @since 2.1.0
	 *
	 * @param object $instance
	 */
	public static function setInstance($instance)
	{
		self::$_instance = $instance;
	}

	/**
	 * getInstance
	 *
	 * Instantiates the class if necessary and returns the instance
	 *
	 * @since 2.1.0
	 *
	 * @return object
	 */
	public static function getInstance()
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * init
	 *
	 * Fills values array with data. Ensures array is empty first.
	 *
	 * @since 2.1.0
	 *
	 * @param array $constants
	 */
	public function init($constants)
	{
		self::$_values = array();
		self::$_values = $constants;
	}

	/**
	 * exists
	 *
	 * Returns true if item exists in constants array
	 *
	 * @since 2.1.0
	 *
	 * @param string $key
	 * @return bool
	 */
	public static function exists($key)
	{
		return array_key_exists($key, self::$_values);
	}

	/**
	 * get
	 *
	 * Gets item from constants array. Returns null if item does not exist
	 *
	 * @since 2.1.0
	 *
	 * @param string $get
	 * @return string
	 */
	public static function get($key)
	{
		return self::exists($key) ? self::$_values[$key] : null;
	}

	/**
	 * set
	 *
	 * Sets item value, may overwrite existing value or add new value
	 *
	 * @since 2.1.0
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set($key, $value)
	{
		self::$_values[$key] = $value;
	}

	/**
	 * clear
	 *
	 * Clears item by unsetting array element
	 *
	 * @since 2.1.0
	 *
	 * @param string $key
	 */
	public static function clear($key)
	{
		if (self::exists($key))
		{
			unset(self::$_values[$key]);
		}
	}
}

?>