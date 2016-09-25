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
	 * extension of the cached files
	 *
	 * @var string
	 */

	protected $_extension = 'cache';

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $directory directory of the cache
	 * @param string $extension extension of the cached files
	 *
	 * @return Cache
	 */

	public function init($directory = null, $extension = null)
	{
		if (strlen($directory))
		{
			$this->_directory = $directory;
		}
		if (strlen($extension))
		{
			$this->_extension = $extension;
		}
		return $this;
	}

	/**
	 * store to cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $key
	 * @param string $value
	 *
	 * @return Cache
	 */

	public function store($key = null, $value = null)
	{
		$path = $this->_getPath($key);
		if ($path && $value)
		{
			file_put_contents($path);
		}
		return $this;
	}

	/**
	 * retrieve from cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $key
	 *
	 * @return string
	 */

	protected function retrieve($key = null)
	{
		$path = $this->_getPath($key);
		if ($path)
		{
			file_get_contents($path);
		}
		return $this;
	}

	/**
	 * validate the cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $key
	 * @param integer $lifetime
	 *
	 * @return boolean
	 */

	public function validate($key = null, $lifetime = 3600)
	{
		$path = $this->_getPath($key);
		return $this->_validate($path, $lifetime);
	}

	/**
	 * clear the cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $key
	 *
	 * @return Cache
	 */

	public function clear($key = null)
	{
		// delete the cache folder by getPath(key) or complete
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

	public function clearExpired($lifetime = 3600)
	{
		// delete expired files in cache folder using _validate();
		return $this;
	}

	/**
	 * get the path
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $key
	 *
	 * @return string
	 */

	public function getPath($key = null)
	{
		if (is_string($key))
		{
			$key =
			[
				$key
			];
		}
		return $this->_directory . '/' . sha1(implode($key)) . '.' . $this->_extension;
	}

	/**
	 * validate the cache
	 *
	 * @since 3.0.0
	 *
	 * @param string $path
	 * @param integer $lifetime
	 *
	 * @return boolean
	 */

	protected function _validate($path = null, $lifetime = 3600)
	{
		return filesize($path) && filectime($path) > $lifetime;
	}
}