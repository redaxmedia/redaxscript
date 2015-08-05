<?php
namespace Redaxscript;

/**
 * parent class to handle a directory in the filesystem
 *
 * @since 2.0.0
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
	 * array of the files to exclude
	 *
	 * @var array
	 */

	protected $_exclude = array(
		'.',
		'..'
	);

	/**
	 * array of the local cache
	 *
	 * @var array
	 */

	protected static $_cache = array();

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $directory name of the directory
	 * @param mixed $exclude files to exclude
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
			$this->_exclude = array_unique(array_merge($this->_exclude, $exclude));
		}

		/* scan directory */

		$this->_directoryArray = $this->_scan($this->_directory);
	}

	/**
	 * get the directory array for further processing
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
		/* use from static cache */

		if (array_key_exists($directory, self::$_cache))
		{
			$directoryArray = self::$_cache[$directory];
		}

		/* else scan directory */

		else
		{
			$directoryArray = scandir($directory);

			/* save to static cache */

			self::$_cache[$directory] = $directoryArray;
		}
		$directoryArray = array_values(array_diff($directoryArray, $this->_exclude));
		return $directoryArray;
	}

	/**
	 * create the directory
	 *
	 * @since 2.1.0
	 *
	 * @param string $directory name of the directory
	 * @param integer $mode file access mode
	 *
	 * @return boolean
	 */

	public function create($directory = null, $mode = 0777)
	{
		$output = false;
		$path = $this->_directory . '/' . $directory;

		/* directory does not exist */

		if (!is_dir($path))
		{
			mkdir($path, $mode);

			/* validate directory was created */

			if (is_dir($path))
			{
				$output = true;
			}
		}
		return $output;
	}

	/**
	 * remove the directory
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory name of the directory
	 */

	public function remove($directory = null)
	{
		/* handle parent directory */

		if (!$directory)
		{
			$path = $this->_directory;
			$directoryArray = $this->_directoryArray;
		}

		/* else handle child */

		else
		{
			$path = $this->_directory . '/' . $directory;
			if (is_dir($path))
			{
				$directoryArray = $this->_scan($path);
			}
			else
			{
				$directoryArray = array();
			}
		}

		/* walk directory array */

		foreach ($directoryArray as $children)
		{
			$childrenPath = $path . '/' . $children;

			/* remove directory */

			if (is_dir($childrenPath))
			{
				$this->remove($directory . '/' . $children);
			}

			/* else unlink file */

			else if (is_file($childrenPath))
			{
				unlink($childrenPath);
			}
		}

		/* remove directory */

		if (is_dir($path))
		{
			rmdir($path);
		}

		/* else unlink file */

		else if (is_file($path))
		{
			unlink($path);
		}
	}
}
