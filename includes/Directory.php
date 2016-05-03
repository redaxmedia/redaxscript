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

	protected $_directoryArray = array();

	/**
	 * static directory cache
	 *
	 * @var array
	 */

	protected static $_directoryCache = null;

	/**
	 * files to be excluded
	 *
	 * @var mixed
	 */

	protected $_exclude = array(
		'.',
		'..'
	);

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $directory name of the directory
	 * @param mixed $exclude files to be excluded
	 */

	public function init($directory = null, $exclude = null)
	{
		$this->_directory = $directory;

		/* handle exclude */

		if (is_string($exclude))
		{
			$exclude = array(
					$exclude
			);
		}
		if (is_array($exclude))
		{
			$this->_exclude = array_merge($this->_exclude, $exclude);
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
	 * remove the directory
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory name of the directory
	 */

	public function remove($directory = null)
	{
		$path = $this->_directory . '/' . $directory;
		if (is_dir($path))
		{
			foreach ($this->_scan($path) as $file)
			{
				if (is_dir($path . '/' . $file))
				{
					$this->remove($directory . '/' . $file);
				}
				else
				{
					unlink($path . '/' . $file);
				}
			}
			rmdir($path);
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
		$directoryArray = array_values(array_diff($directoryArray, $this->_exclude));
		return $directoryArray;
	}
}
