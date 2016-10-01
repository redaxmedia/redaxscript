<?php
namespace Redaxscript\Assetic;

use Redaxscript\Cache;

/**
 * parent class to load and concat assets
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Assetic
 * @author Henry Ruhs
 */

class Loader
{
	/**
	 * collection of the loader
	 *
	 * @var array
	 */

	protected $_collectionArray = [];

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param array $collectionArray
	 *
	 * @return Loader
	 */

	public function init($collectionArray = [])
	{
		if (is_array($collectionArray))
		{
			$this->_collectionArray = $collectionArray;
		}
		return $this;
	}

	/**
	 * get the collection array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getCollectionArray()
	{
		return $this->_collectionArray;
	}

	/**
	 * concat the collection
	 *
	 * @since 3.0.0
	 *
	 * @param string $type
	 *
	 * @return Loader
	 */

	public function concat($type = null)
	{
		$bundleArray = [];
		$pathinfo = null;
		$typeAttribute = $type === 'script' ? 'src' : 'href';
		$typeExtension =  $type === 'script' ? 'js' : 'css';
		$typeDirectory = 'cache';

		/* process collection */

		foreach ($this->_collectionArray as $collectionKey => $attributeArray)
		{
			$pathinfo = pathinfo($attributeArray[$typeAttribute]);
			if (is_file($attributeArray[$typeAttribute]) && $pathinfo['extension'] === $typeExtension)
			{
				$bundleArray[] = $attributeArray[$typeAttribute];
				//unset($this->_collectionArray[$collectionKey]);
			}
		}

		/* cache as needed */

		$cache = new Cache();
		$cache->init($typeDirectory, $typeExtension);

		/* load from cache */

		if ($cache->validate($bundleArray))
		{
			$this->_collectionArray[] =
			[
				$typeAttribute => $cache->getPath($bundleArray)
			];
		}

		/* else store to cache */

		else
		{
			$content = null;
			foreach ($bundleArray as $value)
			{
				$content .= file_get_contents($value);
			}
			$cache->store($bundleArray, $content);
		}
		return $this;
	}

	/**
	 * clear the collection
	 *
	 * @since 3.0.0
	 */

	public function clear()
	{
		$this->_collectionArray = [];
		return $this;
	}
}