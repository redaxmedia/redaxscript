<?php

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

	protected static $_directory = array(
		'.',
		'includes'
	);

	/**
	 * file suffix
	 *
	 * @var string
	 */

	protected static $_fileSuffix = '.php';

	/**
	 * init the class
	 *
	 * @since 2.1.0
	 *
	 * @param mixed $directory specify optional directory to search
	 */

	public static function init($directory = null)
	{
		/* handle directory */

		if (is_array($directory))
		{
			self::$_directory = $directory;
		}
		else if($directory)
		{
			self::$_directory = array(
				$directory
			);
		}

		/* register autoload */

		spl_autoload_register(array(
			__CLASS__,
			'_load'
		));
	}

	/**
	 * load a class file
	 *
	 * @since 2.2.0
	 *
	 * @param string $className name of the class to load
	 */

	protected static function _load($className = null)
	{
		$fileName = str_replace(self::$_nameSpace, '', $className);
		$filePath = str_replace('_', '/', $fileName) . self::$_fileSuffix;

		/* include files as needed */

		foreach (self::$_directory as $directory)
		{
			if (file_exists($directory . '/' . $filePath))
			{
				include($directory . '/' . $filePath);
			}
		}
	}
}
