<?php

/**
 * Redaxscript Read Directory
 *
 * @since 1.3
 *
 * @category Filesystem
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
	 * construct
	 *
	 * @since 1.3
	 *
	 * @param string $directory
	 * @param string|array $ignore
	 */

	public function __construct($directory = '', $ignore = '')
	{
		$this->_directory = $directory;
		$this->_directoryArray = scandir($directory);
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
	 * @since 1.3
	 */

	public function init()
	{
		/* filter directory */

		$this->_directoryArray = array_diff($this->_directoryArray, $this->_ignoreArray);
	}

	/**
	 * getOutput
	 *
	 * @since 1.3
	 *
	 * @param number $key
	 * @return array $_directoryArray
	 */

	public function getOutput($key = '')
	{
		/* single value */

		if (array_key_exists($key, $this->_directoryArray))
		{
			return $this->_directoryArray[$key];
		}

		/* else array */

		else
		{
			return $this->_directoryArray;
		}
	}

	/**
	 * remove
	 *
	 * @param string $directory
	 *
	 * @since 1.3
	 */

	public function remove($directory = '')
	{
		if (!$directory)
		{
			$directory = $this->_directory;
			$directoryArray = $this->_directoryArray;
		}
		else
		{
			$directory = $this->_directory . '/' . $directory;
			$directoryArray = scandir($directory);
			$directoryArray = array_diff($directoryArray, $this->_ignoreArray);
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