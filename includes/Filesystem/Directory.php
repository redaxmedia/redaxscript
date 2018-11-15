<?php
namespace Redaxscript\Filesystem;

use RecursiveIteratorIterator;
use function is_dir;
use function mkdir;
use function rmdir;
use function unlink;

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
	 * @param int $mode file access mode
	 *
	 * @return bool
	 */

	public function createDirectory(string $directory = null, int $mode = 0777) : bool
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
	 * @return bool
	 */

	public function removeDirectory(string $directory = null) : bool
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
	 */

	public function clearDirectory()
	{
		$this->_remove($this->_root);
	}

	/**
	 * remove the directory
	 *
	 * @param string $directory name of the directory
	 */

	protected function _remove(string $directory = null)
	{
		$iterator = $this->_scan($directory);

		/* handle recursive */

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
