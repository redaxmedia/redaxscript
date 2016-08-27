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

	protected $_namespace =
	[
		'Redaxscript\Modules\\',
		'Redaxscript\\'
	];

	/**
	 * project delimiter
	 *
	 * @var string
	 */

	protected $_delimiter = '\\';

	/**
	 * file suffix
	 *
	 * @var string
	 */

	protected $_fileSuffix = '.php';

	/**
	 * directory to search for class files
	 *
	 * @var array
	 */

	protected $_directory =
	[
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

	public function init($directory = null)
	{
		/* handle directory */

		if (is_array($directory))
		{
			$this->_directory = $directory;
		}
		else if (is_string($directory))
		{
			$this->_directory =
			[
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

	protected function _load($className = null)
	{
		$file = str_replace($this->_namespace, '', $className);
		$file = str_replace($this->_delimiter, '/', $file);
		$file .=$this->_fileSuffix;

		/* include as needed */

		foreach ($this->_directory as $directory)
		{
			if (file_exists($directory . '/' . $file))
			{
				include_once($directory . '/' . $file);
			}
		}
	}
}
