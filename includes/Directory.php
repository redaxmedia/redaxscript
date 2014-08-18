<?php
namespace Redaxscript;

/**
 * parent class to handle directories in the filesystem
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

	private $_directory;

	/**
	 * array of directories
	 *
	 * @var array
	 */

	private $_directoryArray;

	/**
	 * array of files to exclude
	 *
	 * @var array
	 */

	protected $_exclude = array(
		'.',
		'..'
	);

	/**
	 * local cache for directories
	 *
	 * @var array
	 */

	private static $_cache = array();

	/**
	 * constructor of the class
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory name of the directory
	 * @param mixed $exclude files to exclude
	 */

	public function __construct($directory = null, $exclude = array())
	{
		$this->_directory = $directory;

		/* handle exclude */

		if (!is_array($exclude))
		{
			$exclude = array(
				$exclude
			);
		}
		$this->_exclude = array_unique(array_merge($this->_exclude, $exclude));
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		/* scan directory */

		$this->_directoryArray = $this->_scan($this->_directory);
	}

	/**
	 * get item from directory
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */

	public function get()
	{
		return $this->_directoryArray;
	}

	/**
	 * scan a directory
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
	 * create a directory
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
		$path = $this->_directory . '/' . $directory;
		$output = false;

		/* check path does not exist */

		if (!file_exists($path))
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
	 * remove a directory
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

		/* else handle child directory or single file */

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

			/* remove if directory */

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

		/* remove if directory */

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
