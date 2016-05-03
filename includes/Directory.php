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
		/* use static cache */

		if (array_key_exists($directory, self::$_directoryCache))
		{
			$directoryArray = self::$_directoryCache[$directory];
		}

		/* else scan directory */

		else
		{
			$directoryArray = scandir($directory);
			self::$_directoryCache[$directory] = $directoryArray;
		}
		$directoryArray = array_values(array_diff($directoryArray, $this->_exclude));
		return $directoryArray;
	}
}
