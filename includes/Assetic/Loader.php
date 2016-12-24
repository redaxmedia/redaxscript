<?php
namespace Redaxscript\Assetic;

use Redaxscript\Cache;
use Redaxscript\Registry;

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
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * collection of the loader
	 *
	 * @var array
	 */

	protected $_collectionArray = [];

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 */

	public function __construct(Registry $registry)
	{
		$this->_registry = $registry;
	}

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
	 * @param array $rewriteArray
	 *
	 * @return Loader
	 */

	public function concat($optionArray = [], $rewriteArray = [])
	{
		$bundleArray = [];
		$restArray = [];

		/* prevent as needed */

		if ($this->_registry->get('noCache'))
		{
			return $this;
		}

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
				$optionArray['attribute'] => $cache->getPath($bundleArray)
			];
			if ($optionArray['extension'] === 'css')
			{
				$this->_collectionArray['bundle']['rel'] = 'stylesheet';
			}
		}

		/* else store to cache */

		else
		{
			$content = $this->_getContent($bundleArray, $rewriteArray);
			$cache->store($bundleArray, $content);
		}
		return $this;
	}

	/**
	 * get the content
	 *
	 * @since 3.0.0
	 *
	 * @param array $bundleArray
	 * @param array $rewriteArray
	 *
	 * @return string
	 */

	protected function _getContent($bundleArray = [], $rewriteArray = [])
	{
		$output = null;

		/* process bundle */

		foreach ($bundleArray as $value)
		{
			$output .= file_get_contents($value);
		}

		/* process rewrite */

		foreach ($rewriteArray as $key => $value)
		{
			$output = str_replace($key, $value, $output);
		}
		return $output;
	}
}