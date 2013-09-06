<?php

/**
 * Redaxscript Directory
 *
 * @since 2.0
 *
 * @category Directory
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Directory
{
	/**
	 * directory
	 * @var string
	 */

	private $_directory;

	/**
	 * directoryArray
	 * @var array
	 */

	private $_directoryArray;

	/**
	 * ignore
	 * @var array
	 */

	protected $_ignoreArray = array(
		'.',
		'..'
	);

	/**
	 * cache
	 * @var array
	 */

	private static $_cache;

	/**
	 * construct
	 *
	 * @since 2.0
	 *
	 * @param string $directory
	 * @param string|array $ignore
	 */

	public function __construct($directory = '', $ignore = '')
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
	 * @since 2.0
	 */

	public function init()
	{
		/* scan directory */

		$this->_directoryArray = $this->_scan($this->_directory);
	}

	/**
	 * getOutput
	 *
	 * @since 2.0
	 *
	 * @param integer $key
	 * @return array
	 */

	public function getOutput($key = '')
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
	 * @since 2.0
	 *
	 * @param string $directory
	 * @return array
	 */

	protected function _scan($directory = '')
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
			$directoryArray = array_diff($directoryArray, $this->_ignoreArray);

			/* save to static cache */

			self::$_cache[$directory] = $directoryArray;
		}
		return $directoryArray;
	}

	/**
	 * remove
	 *
	 * @since 2.0
	 *
	 * @param string $directory
	 */

	public function remove($directory = '')
	{
		/* handle parent directory */

		if (!$directory)
		{
			$directory = $this->_directory;
			$directoryArray = $this->_directoryArray;
		}

		/* else handle children directory */

		else
		{
			$directory = $this->_directory . '/' . $directory;
			$directoryArray = $this->_scan($directory);
		}

		/* walk directory array */

		foreach ($directoryArray as $children)
		{
			$route = $directory . '/' . $children;

			/* remove if directory */

			if (is_dir($route))
			{
				$this->remove($route);
			}

			/* else unlink file */

			else if (is_file($route))
			{
				unlink($route);
			}
		}

		/* remove if directory */

		if (is_dir($directory))
		{
			rmdir($directory);
		}

		/* else unlink file */

		else if (is_file($directory))
		{
			unlink($directory);
		}
	}
}
?>