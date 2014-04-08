<?php

/**
 * The Directory class provides methods to access directories in the filesystem
 *
 * @since 2.0.0
 *
 * @category Directory
 * @package Redaxscript
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Redaxscript_Directory
{
	/**
	 * name of the directory
	 *
	 * @var string
	 */

	private $_directory;

	/**
	 * array containing the directory listing
	 *
	 * @var array
	 */

	private $_directoryArray;

	/**
	 * files to exclude from the directory listing
	 *
	 * @var array
	 */

	protected $_exclude = array(
		'.',
		'..'
	);

	/**
	 * local cache of the directory listing
	 *
	 * @var array
	 */

	private static $_cache;

	/**
	 * constructor
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory Name of the directory to scan
	 * @param string|array $exclude File(s) to exclude from the directory listing
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

		/* call init */

		$this->init();
	}

	/**
	 * initialise the directory object
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		/* scan directory */

		$this->_directoryArray = $this->_scan($this->_directory);
	}

	/**
	 * get a file name from the directory listing
	 *
	 * returns the name of the indexed file, or the entire directory list if the index is not found
	 *
	 * @since 2.0.0
	 *
	 * @param integer $key Index into the directory listing
	 * @return string|array
	 */

	public function get($key = null)
	{
		/* return single value */

		if (array_key_exists($key, $this->_directoryArray))
		{
			return $this->_directoryArray[$key];
		}

		/* else return array */

		else
		{
			return $this->_directoryArray;
		}
	}

	/**
	 * scan a directory and return an array of file names
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory Name of the directory to scan
	 * @return array
	 */

	protected function _scan($directory = null)
	{
		/* use from static cache */

		if (is_array(self::$_cache) && array_key_exists($directory, self::$_cache))
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
	 * create a new directory
	 *
	 * @since 2.1.0
	 *
	 * @param string $directory Name of directory to create
	 * @param integer $mode Access mode, default = 0777
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
	 * remove a selected directory or file
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory A directory or single file to remove
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
?>
