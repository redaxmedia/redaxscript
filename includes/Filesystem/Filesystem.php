<?php
namespace Redaxscript\Filesystem;

use CallbackFilterIterator;
use DirectoryIterator;
use EmptyIterator;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * parent class to handle the filesystem
 *
 * @since 3.2.0
 *
 * @package Redaxscript
 * @category Filesystem
 * @author Henry Ruhs
 */

class Filesystem
{
	/**
	 * value of the root
	 *
	 * @var string
	 */

	protected $_root;

	/**
	 * recursive flag
	 *
	 * @var boolean
	 */

	protected $_recursive;

	/**
	 * array to be filtered
	 *
	 * @var array
	 */

	protected $_filterArray =
	[
		'.',
		'..'
	];

	/**
	 * iterator of the filesystem
	 *
	 * @var object
	 */

	protected $_iterator;

	/**
	 * init the class
	 *
	 * @since 3.2.0
	 *
	 * @param string $root value of the root
	 * @param boolean $recursive recursive flag
	 * @param array $filterArray array to be filtered
	 */

	public function init($root = null, $recursive = false, $filterArray = [])
	{
		$this->_root = $root;
		$this->_recursive = $recursive;
		if (is_array($filterArray))
		{
			$this->_filterArray = array_merge($this->_filterArray, $filterArray);
		}
	}

	/**
	 * get the filesystem iterator
	 *
	 * @since 3.2.0
	 *
	 * @return object
	 */

	public function getIterator()
	{
		if (!$this->_iterator)
		{
			$this->updateIterator();
		}
		if ($this->_recursive)
		{
			return new RecursiveIteratorIterator($this->_iterator, RecursiveIteratorIterator::SELF_FIRST);
		}
		return $this->_iterator;
	}

	/**
	 * get the filesystem array
	 *
	 * @since 3.2.0
	 *
	 * @return array
	 */

	public function getArray()
	{
		$filesystemArray = [];
		$iterator = $this->getIterator();

		/* process iterator */

		foreach ($iterator as $value)
		{
			$filesystemArray[] = $value->getBasename();
		}
		return $filesystemArray;
	}

	/**
	 * get the sorted filesystem array
	 *
	 * @since 3.2.0
	 *
	 * @param integer $flag
	 *
	 * @return array
	 */

	public function getSortArray($flag = SORT_FLAG_CASE)
	{
		$filesystemArray = $this->getArray();
		sort($filesystemArray, $flag);
		return $filesystemArray;
	}

	/**
	 * update the filesystem iterator
	 *
	 * @since 3.2.0
	 */

	public function updateIterator()
	{
		$this->_iterator = $this->_filterIterator($this->_scan($this->_root));
	}

	/**
	 * filter the filesystem iterator
	 *
	 * @since 3.2.0
	 *
	 * @param object $iterator iterator of the filesystem
	 *
	 * @return object
	 */

	protected function _filterIterator($iterator = null)
	{
		if ($this->_recursive)
		{
			return new RecursiveCallbackFilterIterator($iterator, $this->_validateItem());
		}
		return new CallbackFilterIterator($iterator, $this->_validateItem());
	}

	/**
	 * validate the filesystem item
	 *
	 * @since 3.2.0
	 *
	 * @return object
	 */

	protected function _validateItem()
	{
		return function ($item)
		{
			return !in_array($item->getFileName(), $this->_filterArray);
		};
	}

	/**
	 * scan the filesystem
	 *
	 * @since 3.2.0
	 *
	 * @param string $directory name of the directory
	 *
	 * @return object
	 */

	protected function _scan($directory = null)
	{
		if (is_dir($directory))
		{
			if ($this->_recursive)
			{
				return new RecursiveDirectoryIterator($directory);
			}
			return new DirectoryIterator($directory);
		}
		return new EmptyIterator();
	}
}
