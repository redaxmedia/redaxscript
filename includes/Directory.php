<?php
namespace Redaxscript;

/**
 * parent class to handle a directory in the filesystem
 *
 * @since 3.0.0
 *
 * @category Directory
 * @package Redaxscript
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Directory
{
	/**
	 * name of the directory
	 *
	 * @var string
	 */

	protected $_directory;

	/**
	 * array of the directory
	 *
	 * @var array
	 */

	protected $_directoryArray = [];

	/**
	 * static directory cache
	 *
	 * @var array
	 */

	protected static $_directoryCache = [];

	/**
	 * files to be excluded
	 *
	 * @var array
	 */

	protected $_excludeArray =
	[
		'.',
		'..'
	];

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $directory name of the directory
	 * @param array $excludeArray files to be excluded
	 */

	public function init($directory = null, $excludeArray = [])
	{
		$this->_directory = $directory;

		/* handle exclude */

		if (is_array($excludeArray))
		{
			$this->_excludeArray = array_merge($this->_excludeArray, $excludeArray);
		}

		/* scan directory */

		$this->_directoryArray = $this->_scan($this->_directory);
	}

	/**
	 * get the directory array
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */

	public function getArray()
	{
		return $this->_directoryArray;
	}

	/**
	 * create the directory
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory name of the directory
	 * @param integer $mode file access mode
	 */

	public function create($directory = null, $mode = 0777)
	{
		$path = $this->_directory . '/' . $directory;
		if (!is_dir($path))
		{
			mkdir($path, $mode);
		}
	}

	/**
	 * put content to file
	 *
	 * @since 3.0.0
	 *
	 * @param string $path name of the path
	 * @param string $content content of the file
	 */

	public function put($path = null, $content = null)
	{
		if ($path && $content)
		{
			file_put_contents($this->_directory . '/' . $path, $content);
		}
	}

	/**
	 * remove the path
	 *
	 * @since 3.0.0
	 *
	 * @param string $path name of the $path
	 */

	public function remove($path = null)
	{
		if ($path)
		{
			$this->_remove($this->_directory . '/' . $path);
		}
	}

	/**
	 * clear the directory
	 *
	 * @since 3.0.0
	 */

	public function clear()
	{
		$this->_remove($this->_directory);
	}

	/**
	 * remove the path
	 *
	 * @since 3.0.0
	 *
	 * @param string $path name of the path
	 */

	protected function _remove($path = null)
	{
		if (is_dir($path))
		{
			foreach ($this->_scan($path) as $value)
			{
				$this->_remove($path . '/' . $value);
			}
			if ($path !== $this->_directory)
			{
				rmdir($path);
			}
		}
		else if (is_file($path))
		{
			unlink($path);
		}
	}

	/**
	 * scan the directory
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory name of the directory
	 *
	 * @return array
	 */

	protected function _scan($directory = null)
	{
		$realpath = realpath($directory);

		/* use static cache */

		if (array_key_exists($realpath, self::$_directoryCache))
		{
			$directoryArray = self::$_directoryCache[$realpath];
		}

		/* else scan directory */

		else
		{
			$directoryArray = scandir($directory);
			self::$_directoryCache[$realpath] = $directoryArray;
		}
		$directoryArray = array_values(array_diff($directoryArray, $this->_excludeArray));
		return $directoryArray;
	}
}
