<?php
namespace Redaxscript\Asset;

use Redaxscript\Filesystem;
use Redaxscript\Registry;
use function file_get_contents;
use function is_file;
use function pathinfo;
use function str_replace;

/**
 * parent class to load and concat assets
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Asset
 * @author Henry Ruhs
 */

class Loader
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
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
	 * @return self
	 */

	public function init(array $collectionArray = []) : self
	{
		$this->_collectionArray = $collectionArray;
		return $this;
	}

	/**
	 * get the collection array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getCollectionArray() : array
	{
		return $this->_collectionArray;
	}

	/**
	 * set the collection array
	 *
	 * @since 3.0.0
	 *
	 * @param array $collectionArray
	 */

	public function setCollectionArray(array $collectionArray = [])
	{
		$this->_collectionArray = $collectionArray;
	}

	/**
	 * concat the collection
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 * @param array $rewriteArray
	 *
	 * @return self
	 */

	public function concat(array $optionArray = [], array $rewriteArray = []) : self
	{
		$collectionArray = $this->getCollectionArray();
		$bundleArray = [];
		$restArray = [];

		/* prevent as needed */

		if ($this->_registry->get('noCache'))
		{
			return $this;
		}

		/* process collection */

		foreach ($collectionArray as $attributeArray)
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

		$this->_handleCache($rewriteArray, $bundleArray, $restArray, $optionArray);
		return $this;
	}

	/**
	 * handle the cache
	 *
	 * @since 4.0.0
	 *
	 * @param array $rewriteArray
	 * @param array $bundleArray
	 * @param array $restArray
	 * @param array $optionArray
	 */

	public function _handleCache(array $rewriteArray = [], array $bundleArray = [], array $restArray = [], array $optionArray = [])
	{
		$cacheFilesystem = new Filesystem\Cache();
		$cacheFilesystem->init($optionArray['directory'], $optionArray['extension']);

		/* load from cache */

		if ($cacheFilesystem->validate($bundleArray, $optionArray['lifetime']))
		{
			$collectionArray = $restArray;
			$collectionArray['bundle'] =
			[
				$optionArray['attribute'] => $cacheFilesystem->getPath($bundleArray, '/')
			];
			if ($optionArray['extension'] === 'css')
			{
				$collectionArray['bundle']['rel'] = 'stylesheet';
			}
			$this->setCollectionArray($collectionArray);
		}

		/* else store to cache */

		else
		{
			$content = $this->_getContent($bundleArray, $rewriteArray);
			$cacheFilesystem->store($bundleArray, $content);
		}
	}

	/**
	 * get the content
	 *
	 * @since 3.0.0
	 *
	 * @param array $bundleArray
	 * @param array $rewriteArray
	 *
	 * @return string|null
	 */

	protected function _getContent(array $bundleArray = [], array $rewriteArray = []) : ?string
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
