<?php

/**
 * parent class to automatically load class files as needed
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
	 * project namespace
	 *
	 * @var string
	 */

	protected static $_nameSpace = 'Redaxscript_';

	/**
	 * directory to search for class files
	 *
	 * @var string
	 */

	protected static $_directory = 'includes';

	/**
	 * filename suffix
	 *
	 * @var string
	 */

	protected static $_fileSuffix = '.php';

	/**
	 * register the load method as the spl autoloader
	 *
	 * @since 2.1.0
	 *
	 * @param string $directory specify a optional directory to search
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
	 * load a class file
	 *
	 * @since 2.1.0
	 *
	 * @param string $className name of the class to load
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
