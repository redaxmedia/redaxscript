<?php
namespace Redaxscript;

/**
 * abstract class to build a singleton class
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Singleton
 * @author Henry Ruhs
 */

abstract class Singleton
{
	/**
	 * instance of the class
	 *
	 * @var array
	 */

	protected static $_instance;

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 */

	private function __construct()
	{
	}

	/**
	 * get the instance
	 *
	 * @since 2.2.0
	 *
	 * @return object
	 */

	public static function getInstance()
	{
		$className = get_called_class();

		/* instance by class */

		if (!array_key_exists($className, static::$_instance))
		{
			static::$_instance[$className] = new static();
		}
		return static::$_instance[$className];
	}

	/**
	 * reset the instance
	 *
	 * @since 2.2.0
	 *
	 * @return object
	 */

	public static function reset()
	{
		self::$_instance = null;
	}
}
