<?php
namespace Redaxscript\Filesystem;

use RecursiveIteratorIterator;

/**
 * children class to handle a directory in the filesystem
 *
 * @since 3.2.0
 *
 * @category Filesystem
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Directory extends File
{
	/**
	 * create the directory
	 *
	 * @since 3.2.0
	 *
	 * @param string $directory name of the directory
	 * @param integer $mode file access mode
	 *
	 * @return boolean
	 */

	public function createDirectory($directory = null, $mode = 0777)
	{
		$path = $this->_root . DIRECTORY_SEPARATOR . $directory;
		return !is_dir($path) && mkdir($path, $mode);
	}

	/**
	 * remove the directory
	 *
	 * @since 3.2.0
	 *
	 * @param string $directory name of the directory
	 *
	 * @return boolean
	 */

	public function removeDirectory($directory = null)
	{
		if ($directory)
		{
			$path = $this->_root . DIRECTORY_SEPARATOR . $directory;
			$this->_remove($path);
			return rmdir($path);
		}
		return false;
	}

	/**
	 * clear the directory
	 *
	 * @since 3.2.0
	 *
	 * @return boolean
	 */

	public function clearDirectory()
	{
		return $this->_remove($this->_root);
	}

	/**
	 * remove the directory
	 *
	 * @param string $directory name of the directory
	 */

	protected function _remove($directory = null)
	{
		$iterator = $this->_scan($directory);
		if ($this->_recursive)
		{
			$iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
		}

		/* process iterator */

		foreach ($iterator as $item)
		{
			$path = $item->getPathName();
			$item->isDir() ? rmdir($path) : unlink($path);
		}
	}
}
