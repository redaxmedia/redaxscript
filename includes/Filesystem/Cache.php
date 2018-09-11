<?php
namespace Redaxscript\Filesystem;

/**
 * parent class to handle cached files
 *
 * @since 3.0.0
 *
 * @category Filesystem
 * @package Redaxscript
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
	 * @param string $directory name of the directory
	 * @param string $extension extension of the cached files
	 *
	 * @return self
	 */

	public function init(string $directory = null, string $extension = null) : self
	{
		if (strlen($directory))
		{
			$this->_directory = $directory;
		}
		if (!is_dir($this->_directory))
		{
			mkdir($this->_directory);
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
	 * @param string|array $bundle key or collection of the bundle
	 * @param string $separator directory separator
	 *
	 * @return string
	 */

	public function getPath($bundle = null, string $separator = DIRECTORY_SEPARATOR) : string
	{
		return $this->_directory . $separator . $this->_getFile($bundle);
	}

	/**
	 * store to cache
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $bundle key or collection of the bundle
	 * @param string $content content of the bundle
	 *
	 * @return self
	 */

	public function store($bundle = null, string $content = null) : self
	{
		if ($bundle)
		{
			$cacheFile = new File();
			$cacheFile->init($this->_directory);
			$cacheFile->writeFile($this->_getFile($bundle), $content);
		}
		return $this;
	}

	/**
	 * retrieve from cache
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $bundle key or collection of the bundle
	 *
	 * @return string|null
	 */

	public function retrieve($bundle = null) : ?string
	{
		if ($bundle)
		{
			$cacheFile = new File();
			$cacheFile->init($this->_directory);
			return $cacheFile->readFile($this->_getFile($bundle));
		}
		return null;
	}

	/**
	 * validate the cache
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $bundle key or collection of the bundle
	 * @param int $lifetime lifetime of the bundle
	 *
	 * @return bool
	 */

	public function validate($bundle = null, int $lifetime = 3600) : bool
	{
		if ($bundle)
		{
			return $this->_validateFile($this->_getFile($bundle), $lifetime);
		}
		return false;
	}

	/**
	 * clear the cache
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $bundle key or collection of the bundle
	 *
	 * @return self
	 */

	public function clear($bundle = null) : self
	{
		$cacheFilesystem = new Directory();
		$cacheFilesystem->init($this->_directory);
		if ($bundle)
		{
			$cacheFilesystem->removeFile($this->_getFile($bundle));
		}
		else
		{
			$cacheFilesystem->clearDirectory();
		}
		return $this;
	}

	/**
	 * clear the invalid cache
	 *
	 * @since 3.0.0
	 *
	 * @param int $lifetime lifetime of the bundle
	 *
	 * @return self
	 */

	public function clearInvalid(int $lifetime = 3600) : self
	{
		$cacheFile = new File();
		$cacheFile->init($this->_directory);
		$cacheFileArray = $cacheFile->getArray();

		/* process cache */

		foreach ($cacheFileArray as $file)
		{
			if (!$this->_validateFile($file, $lifetime))
			{
				$cacheFile->removeFile($file);
			}
		}
		return $this;
	}

	/**
	 * get the file
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $bundle key or collection of the bundle
	 *
	 * @return string
	 */

	protected function _getFile($bundle = null) : string
	{
		return sha1(implode('-', (array)$bundle)) . '.' . $this->_extension;
	}

	/**
	 * validate the file
	 *
	 * @since 3.2.0
	 *
	 * @param string $file name of the file
	 * @param int $lifetime lifetime of the file
	 *
	 * @return bool
	 */

	protected function _validateFile(string $file = null, int $lifetime = 3600) : bool
	{
		$path = $this->_directory . DIRECTORY_SEPARATOR . $file;
		return is_file($path) && filesize($path) && filemtime($path) > time() - $lifetime;
	}
}
