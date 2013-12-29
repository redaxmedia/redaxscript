<?php

/**
 * Redaxscript Autoloader
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Autoloader
 * @author Henry Ruhs
 */

class Redaxscript_Autoloader
{
	/**
	 * nameSpace
	 * @var string
	 */

	protected static $_nameSpace = 'Redaxscript_';

	/**
	 * fileSuffix
	 * @var string
	 */

	protected static $_fileSuffix = '.php';

	/**
	 * init
	 *
	 * @since 2.1.0
	 */

	public static function init()
	{
		spl_autoload_register(array(__CLASS__, '_load'));
	}

	/**
	 * load
	 *
	 * @since 2.1.0
	 *
	 * @param string $className
	 */

	protected static function _load($className = '')
	{
		$fileName = strtolower(str_replace(self::$_nameSpace, '', $className)) . self::$_fileSuffix;

		/* include files as needed */

		if (file_exists('includes/' . $fileName))
		{
			include('includes/' . $fileName);
		}
	}
}
?>