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
	 * directory
	 * @var string
	 */

	protected static $_directory = 'includes';

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

	public static function init($directory = null)
	{
		spl_autoload_register(array(__CLASS__, '_load'));

		/* directory exists */

		if (is_dir($directory))
		{
			self::$_directory = $directory;
		}
	}

	/**
	 * load
	 *
	 * @since 2.1.0
	 *
	 * @param string $className
	 */

	protected static function _load($className = null)
	{
		$fileName = strtolower(str_replace(self::$_nameSpace, '', $className)) . self::$_fileSuffix;

		/* include files as needed */

		if (file_exists(self::$_directory . '/' . $fileName))
		{
			include(self::$_directory . '/' . $fileName);
		}
	}
}
?>