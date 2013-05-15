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
	 * @param string|number $key
	 * @return array $_directoryArray
	 */

	public function getOutput($key = '')
	{
		if (array_key_exists($key, $this->_directoryArray))
		{
			return $this->_directoryArray[$key];
		}
		else
		{
			return $this->_directoryArray;
		}
	}

	/**
	 * remove
	 *
	 * @param string $directory
	 * @param boolean $recursive
	 *
	 * @since 1.3
	 */

	public function remove($directory = 'this', $recursive = true)
	{
		if ($directory === 'this')
		{
			$directory = $this->_directory;
			$directoryArray = $this->_directoryArray;
		}
		else
		{
			$directoryArray = scandir($directory);
		}

		/* walk directory array */

		if (is_array($directoryArray))
		{
			foreach ($directoryArray as $value)
			{
				$route = $directory . '/' . $value;

				/* remove if directory */

				if (is_dir($route))
				{
					
					$this->remove($route, true);
				}

				/* else unlink file */

				else
				{
					unlink($route);
				}
			}
		}

		/* else remove directory */

		else if (is_dir($directory) && $recursive === true)
		{
			rmdir($directory);
		}
	}
}
?>