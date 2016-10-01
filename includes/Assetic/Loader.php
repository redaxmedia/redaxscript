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
	 * @param array $optionArray
	 *
	 * @return Loader
	 */

	public function concat($optionArray = [])
	{
		$bundleArray = [];
		$restArray = [];

		/* process collection */

		foreach ($this->_collectionArray as $collectionKey => $attributeArray)
		{
			$path = $attributeArray[$optionArray['attribute']];
			$fileArray = pathinfo($path);
			if (is_file($path) && $fileArray['extension'] === $optionArray['extension'])
			{
				$bundleArray[] = $attributeArray[$optionArray['attribute']];
			}
			else
			{
				$restArray[] = $attributeArray;
			}
		}

		/* cache as needed */

		$cache = new Cache();
		$cache->init($optionArray['directory'], $optionArray['extension']);

		/* load from cache */

		if ($cache->validate($bundleArray, $optionArray['lifetime']))
		{
			$this->_collectionArray = $restArray;
			$this->_collectionArray['bundle'] =
			[
				$optionArray['attribute'] => $cache->getPath($bundleArray),
			];
			if ($optionArray['extension'] === 'css')
			{
				$this->_collectionArray['bundle']['rel'] = 'stylesheet';
			}
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