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
	 * get the path
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $bundle key or collection of the bundle
	 *
	 * @return string
	 */

	public function getPath($bundle = null)
	{
		return $this->_directory . '/' . $this->_getFile($bundle);
	}

	/**
	 * store to cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $bundle key or collection of the bundle
	 * @param string $content content of the bundle
	 *
	 * @return Cache
	 */

	public function store($bundle = null, $content = null)
	{
		$path = $this->getPath($bundle);
		if ($path && $content)
		{
			file_put_contents($path, $content);
		}
		return $this;
	}

	/**
	 * retrieve from cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $bundle key or collection of the bundle
	 *
	 * @return string
	 */

	public function retrieve($bundle = null)
	{
		$path = $this->getPath($bundle);
		if ($path)
		{
			return file_get_contents($path);
		}
	}

	/**
	 * validate the cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $bundle key or collection of the bundle
	 * @param integer $lifetime lifetime of the bundle
	 *
	 * @return boolean
	 */

	public function validate($bundle = null, $lifetime = 3600)
	{
		$path = $this->getPath($bundle);
		return $this->_validateFile($path, $lifetime);
	}

	/**
	 * clear the cache
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $bundle key or collection of the bundle
	 *
	 * @return Cache
	 */

	public function clear($bundle = null)
	{
		$cacheDirectory = new Directory();
		$cacheDirectory->init($this->_directory);
		if ($bundle)
		{
			$file = $this->_getFile($bundle);
			$cacheDirectory->remove($file);
		}
		else
		{
			$cacheDirectory->clear();
		}
		return $this;
	}

	/**
	 * clear the invalid cache
	 *
	 * @since 3.0.0
	 *
	 * @param integer $lifetime lifetime of the bundle
	 *
	 * @return Cache
	 */

	public function clearInvalid($lifetime = 3600)
	{
		$cacheDirectory = new Directory();
		$cacheDirectory->init($this->_directory);
		$cacheDirectoryArray = $cacheDirectory->getArray();

		/* process cache */

		foreach ($cacheDirectoryArray as $value)
		{
			$path = $this->_directory . '/' . $value;
			if (is_file($path) && !$this->_validateFile($path, $lifetime))
			{
				$cacheDirectory->remove($value);
			}
		}
		return $this;
	}

	/**
	 * get the file
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $bundle key or collection of the bundle
	 *
	 * @return string
	 */

	protected function _getFile($bundle = null)
	{
		if (is_string($bundle))
		{
			$bundle =
			[
				$bundle
			];
		}
		return sha1(implode('-', $bundle)) . '.' . $this->_extension;
	}

	/**
	 * validate the file
	 *
	 * @since 3.0.0
	 *
	 * @param string $path path of the bundle
	 * @param integer $lifetime lifetime of the bundle
	 *
	 * @return boolean
	 */

	protected function _validateFile($path = null, $lifetime = 3600)
	{
		return filesize($path) && filemtime($path) > time() - $lifetime;
	}
}