<?php

/**
 * Redaxscript Registry
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
	 * array of registry values
	 *
	 * @var array
	 */

	protected static $_values = array();

	/**
	 * instance
	 *
	 * singleton instance of the class
	 *
	 * @var object
	 */

	protected static $_instance = null;

	/**
	 * construct
	 *
	 * construct is private to ensure singleton
	 *
	 * @since 2.1.0
	 */

	private function __construct()
	{
	}

	/**
	 * init
	 *
	 * fills values array with data
	 *
	 * @since 2.1.0
	 *
	 * @param array $values
	 */

	public function init($values = array())
	{
		if (is_array($values))
		{
			self::$_values = $values;
		}
	}

	/**
	 * get
	 *
	 * gets item from values array
	 *
	 * @since 2.1.0
	 *
	 * @param string $get
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
	 * set
	 *
	 * sets item to values array
	 *
	 * @since 2.1.0
	 *
	 * @param string $key
	 * @param mixed $value
	 */

	public static function set($key = null, $value = null)
	{
		self::$_values[$key] = $value;
	}

	/**
	 * instance
	 *
	 * creates and returns the instance
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