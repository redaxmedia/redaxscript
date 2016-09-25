<?php
namespace Redaxscript;

/**
 * parent class to handle cached files
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Cache
 * @author Henry Ruhs
 */

class Cache
{
	/**
	 * directory of the cache
	 *
	 * @var string
	 */

	protected $_directory = 'cache';

	/**
	 * extension of files
	 *
	 * @var string
	 */

	protected $_extension = 'cache';

	/**
	 * lifetime in seconds
	 *
	 * @var string
	 */

	protected $_lifetime = 3600;

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory directory of the cache
	 *
	 * @return Cache
	 */

	public function init($directory = null)
	{
		if ($directory)
		{
			$this->_directory = $directory;
		}
		return $this;
	}

	/**
	 * store to cache
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return Cache
	 */

	public function store($key = null, $value = null)
	{
		// plain store $value to _getFilename($key);
		return $this;
	}

	/**
	 * retrieve from cache
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param integer $lifetime
	 *
	 * @return string
	 */

	protected function retrieve($key = null, $lifetime = null)
	{
		// return $value from _getFilename($key);
		//return $output;
	}

	/**
	 * store to file to cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $file
	 *
	 * @return Cache
	 */

	public function storeFile($file = null)
	{
		// use _getFilename($file) from the collection of the file
		// read content from files and put it to the store($key, $value) method
		return $this;
	}

	/**
	 * retrieve from cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $file
	 * @param integer $lifetime
	 *
	 * @return string
	 */

	protected function retrieveFile($file = null, $lifetime = null)
	{
		// generate a hash from the collection of the file names using _getHash()
		// read content from files and put it to the retrieve() method
		//return $output;
	}

	/**
	 * validate the cache
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param integer $lifetime
	 *
	 * return boolean
	 */

	public function validate($key = null, $lifetime = null)
	{
		// use _getFilename($key) and validate for the lifetime and that file has content
	}

	/**
	 * clear the cache
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 *
	 * @return Cache
	 */

	public function clear($key = null)
	{
		// delete the whole cache folder or _getFilename($key) without acceptation
		return $this;
	}

	/**
	 * clear the expired cache
	 *
	 * @since 3.0.0
	 *
	 * @param integer $lifetime
	 *
	 * @return Cache
	 */

	public function clearExpired($lifetime = null)
	{
		// delete the expired files in cache folder using validate();
		return $this;
	}

	/**
	 * get filename
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $key
	 *
	 * @return string
	 */

	protected function _getFilename($key = null)
	{
		if (is_string($key))
		{
			$key =
			[
				$key
			];
		}
		return sha1(implode($key)) . '.' . $this->_extension;
	}
}