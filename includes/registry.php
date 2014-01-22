<?php

/**
 * registry
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
	 * values
	 *
	 * the array of registry values
	 *
	 * @var array
	 */

	protected static $_values = array();

	/**
	 * instance
	 *
	 * the singleton instance of the class
	 *
	 * @var Redaxscript_Registry
	 */

	protected static $_instance = null;

	/**
	 * construct
	 *
	 * constructor is private to ensure singleton
	 *
	 * @since 2.1.0
	 */

	private function __construct()
	{
	}

	/**
	 * getInstance
	 *
	 * instantiates the class if necessary and returns the instance
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
	 * fills values array with data, ensures array is empty first.
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
	 * returns true if item exists in constants array
	 *
	 * @since 2.1.0
	 *
	 * @param string $key
	 * @return boolean
	 */

	public static function exists($key)
	{
		return array_key_exists($key, self::$_values);
	}

	/**
	 * get
	 *
	 * gets item from constants array, returns null if item does not exist
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
	 * sets item value, may overwrite existing value or add new value
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
}

?>