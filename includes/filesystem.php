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
	 * @var array
	 */

	private $_directory;

	/**
	 * ignore
	 * @var array
	 */

	protected $_ignore = array(
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
		$this->_directory = scandir($directory);
		if (!is_array($ignore))
		{
			$ignore = array(
				$ignore
			);
		}
		$this->_ignore = array_merge($this->_ignore, $ignore);

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

		$this->_directory = array_diff($this->_directory, $this->_ignore);
	}

	/**
	 * getOutput
	 *
	 * @since 1.3
	 *
	 * @return array $_directory
	 */

	public function getOutput()
	{
		return $this->_directory;
	}

	/**
	 * remove
	 *
	 * @param array $directory
	 * @param boolean $recursive
	 *
	 * @since 1.3
	 */

	public function remove($directory = 'this', $recursive = true)
	{
		if ($directory === 'this')
		{
			$directory = $this->_directory;
		}
		else
		{
			$directory = scandir($directory);
		}

		/* walk directory array */

		if (is_array($directory))
		{
			foreach ($directory as $value)
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