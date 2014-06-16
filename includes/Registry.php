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

class Redaxscript_Registry
{
	/**
	 * instance of the class
	 *
	 * @var object
	 */

	protected static $_instance = null;

	/**
	 * array of registry values
	 *
	 * @var array
	 */

	protected static $_values = array();

	/**
	 * constructor of the class
	 *
	 * @since 2.1.0
	 */

	private function __construct()
	{
	}

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

	/**
	 * instance of the class
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

	/**
	 * reset the instance
	 *
	 * @since 2.1.0
	 *
	 * @return object
	 */

	public static function reset()
	{
		self::$_instance = null;
	}
}