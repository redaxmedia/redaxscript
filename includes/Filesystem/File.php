<?php
namespace Redaxscript\Filesystem;

/**
 * children class to handle a file in the filesystem
 *
 * @since 3.2.0
 *
 * @package Redaxscript
 * @category Filesystem
 * @author Henry Ruhs
 */

class File extends Filesystem
{
	/**
	 * create the file
	 *
	 * @since 3.2.0
	 *
	 * @param string $file name of the file
	 * @param integer $mode file access mode
	 *
	 * @return boolean
	 */

	public function createFile($file = null, $mode = 0777)
	{
		$path = $this->_root . DIRECTORY_SEPARATOR . $file;
		return !is_file($path) && touch($path) && chmod($path, $mode);
	}

	/**
	 * read content of file
	 *
	 * @since 3.0.0
	 *
	 * @param string $file name of the file
	 *
	 * @return string|boolean
	 */

	public function readFile($file = null)
	{
		$path = $this->_root . DIRECTORY_SEPARATOR . $file;
		if (is_file($path))
		{
			return file_get_contents($path);
		}
		return false;
	}

	/**
	 * render content of file
	 *
	 * @since 3.0.0
	 *
	 * @param string $file name of the file
	 *
	 * @return string
	 */

	public function renderFile($file = null)
	{
		$path = $this->_root . DIRECTORY_SEPARATOR . $file;
		ob_start();
		if (is_file($path))
		{
			include($path);
		}
		return ob_get_clean();
	}

	/**
	 * write content to file
	 *
	 * @since 3.0.0
	 *
	 * @param string $file name of the file
	 * @param string $content content of the file
	 *
	 * @return boolean
	 */

	public function writeFile($file = null, $content = null)
	{
		$path = $this->_root . DIRECTORY_SEPARATOR . $file;
		return strlen($content) && file_put_contents($path, $content) > 0;
	}

	/**
	 * remove the file
	 *
	 * @since 3.2.0
	 *
	 * @param string $file name of the file
	 *
	 * @return boolean
	 */

	public function removeFile($file = null)
	{
		$path = $this->_root . DIRECTORY_SEPARATOR . $file;
		return is_file($path) && unlink($path);
	}
}
