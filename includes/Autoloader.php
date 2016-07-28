<?php
namespace Redaxscript;

/**
 * parent class to automatically load required class files
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Autoloader
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Autoloader
{
	/**
	 * project namespace
	 *
	 * @var string
	 */

	protected static $_namespace = [
		'Redaxscript\Modules\\',
		'Redaxscript\\'
	];

	/**
	 * project class delimiter
	 *
	 * @var string
	 */

	protected static $_delimiter = '\\';

	/**
	 * file suffix
	 *
	 * @var string
	 */

	protected static $_fileSuffix = '.php';

	/**
	 * directory to search for class files
	 *
	 * @var array
	 */

	protected static $_directory = [
		'includes',
		'libraries',
		'modules'
	];

	/**
	 * init the class
	 *
	 * @since 2.1.0
	 *
	 * @param mixed $directory optional directory to search
	 */

	public static function init($directory = null)
	{
		/* handle directory */

		if (is_array($directory))
		{
			self::$_directory = $directory;
		}
		else if (is_string($directory))
		{
			self::$_directory = [
				$directory
			];
		}

		/* register autoload */

		spl_autoload_register(
		[
			__CLASS__,
			'_load'
		]);
	}

	/**
	 * load a class file
	 *
	 * @since 2.6.0
	 *
	 * @param string $className name of the class to load
	 */

	protected static function _load($className = null)
	{
		$file = str_replace(self::$_namespace, '', $className);
		$file = str_replace(self::$_delimiter, '/', $file);
		$file .= self::$_fileSuffix;

		/* include files as needed */

		foreach (self::$_directory as $directory)
		{
			if (file_exists($directory . '/' . $file))
			{
				include_once($directory . '/' . $file);
			}
		}
	}
}
