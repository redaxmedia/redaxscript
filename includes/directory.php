<?php

/**
 * Redaxscript Directory
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
	 * directory
	 *
	 * @var string
	 */

	private $_directory;

	/**
	 * directoryArray
	 *
	 * @var array
	 */

	private $_directoryArray;

	/**
	 * ignore
	 *
	 * @var array
	 */

	protected $_ignoreArray = array(
		'.',
		'..'
	);

	/**
	 * cache
	 *
	 * @var array
	 */

	private static $_cache;

	/**
	 * construct
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory
	 * @param string|array $ignore
	 */

	public function __construct($directory = null, $ignore = null)
	{
		$this->_directory = $directory;

		/* handle and merge ignore */

		if (!is_array($ignore))
		{
			$ignore = array(
				$ignore
			);
		}
		$this->_ignoreArray = array_merge($this->_ignoreArray, $ignore);

		/* call init */

		$this->init();
	}

	/**
	 * init
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		/* scan directory */

		$this->_directoryArray = $this->_scan($this->_directory);
	}

	/**
	 * getOutput
	 *
	 * @since 2.0.0
	 *
	 * @param integer $key
	 * @return array
	 */

	public function getOutput($key = null)
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
	 * scan
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory
	 * @return array
	 */

	protected function _scan($directory = null)
	{
		/* use from static cache */

		if (is_array(self::$_cache) && array_key_exists($directory, self::$_cache))
		{
			$directoryArray = array_values(array_diff(self::$_cache[$directory], $this->_ignoreArray));
		}

		/* else scan directory */

		else
		{
			$directoryArray = scandir($directory);

			/* save to static cache */

			self::$_cache[$directory] = $directoryArray;

			$directoryArray = array_values(array_diff($directoryArray, $this->_ignoreArray));
		}
		return $directoryArray;
	}

	/**
	 * create
	 *
	 * @since 2.1.0
	 *
	 * @param string $directory
	 * @param integer $mode
	 * @return boolean
	 */

	public function create($directory = '', $mode = 0777)
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
	 * remove
	 *
	 * @since 2.0.0
	 *
	 * @param string $directory
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
			$route = $path . '/' . $children;

			/* remove if directory */

			if (is_dir($route))
			{
				$this->remove($directory . '/' . $children);
			}

			/* else unlink file */

			else if (is_file($route))
			{
				unlink($route);
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