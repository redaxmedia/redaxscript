<?php
namespace Redaxscript;

use function array_key_exists;

/**
 * abstract class to create a singleton class
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Singleton
 * @author Henry Ruhs
 *
 * @codeCoverageIgnore
 */

abstract class Singleton
{
	/**
	 * array of static instances
	 *
	 * @var array
	 */

	protected static $_instanceArray = [];

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 */

	private function __construct()
	{
	}

	/**
	 * clone of the class
	 *
	 * @since 5.0.0
	 */

	private function __clone()
	{
	}

	/**
	 * get the instance
	 *
	 * @since 5.0.0
	 *
	 * @return static
	 */

	public static function getInstance()
	{
		$className = static::class;

		/* create instance */

		if (!array_key_exists($className, static::$_instanceArray))
		{
			static::$_instanceArray[$className] = new static();
		}
		return static::$_instanceArray[$className];
	}

	/**
	 * clear the instance
	 *
	 * @since 3.0.0
	 */

	public static function clearInstance() : void
	{
		$className = static::class;
		self::$_instanceArray[$className] = null;
	}
}
