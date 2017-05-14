<?php
namespace Redaxscript\Head;

use Redaxscript\Asset;
use Redaxscript\Html;
use Redaxscript\Registry;

/**
 * children class to create the link tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @method append($attribute = null, $value = null)
 * @method prepend($attribute = null, $value = null)
 * @method clear()
 */

class Link extends HeadAbstract
{
	/**
	 * options of the link
	 *
	 * @var string
	 */

	protected static $_optionArray =
	[
		'directory' => 'cache/styles',
		'extension' => 'css',
		'attribute' => 'href',
		'lifetime' => 86400
	];

	/**
	 * rewrite of the link
	 *
	 * @var string
	 */

	protected static $_rewriteArray = [];

	/**
	 * append link file
	 *
	 * @since 3.0.0
	 *
	 * @param string $reference
	 *
	 * @return Link
	 */

	public function appendFile($reference = null)
	{
		$this->append(
		[
			'href' => $reference,
			'rel' => 'stylesheet'
		]);
		return $this;
	}

	/**
	 * prepend link file
	 *
	 * @since 3.0.0
	 *
	 * @param string $reference
	 *
	 * @return Link
	 */

	public function prependFile($reference = null)
	{
		$this->prepend(
		[
			'href' => $reference,
			'rel' => 'stylesheet'
		]);
		return $this;
	}

	/**
	 * remove link file
	 *
	 * @since 3.0.0
	 *
	 * @param string $reference
	 *
	 * @return Link
	 */

	public function removeFile($reference = null)
	{
		$this->remove('href', $reference);
		return $this;
	}

	/**
	 * rewrite the link
	 *
	 * @since 3.0.0
	 *
	 * @param array $pathArray
	 *
	 * @return Link
	 */

	public function rewrite($pathArray = [])
	{
		$rewriteArray = $this->_getRewriteArray();
		if (!is_array($rewriteArray))
		{
			$rewriteArray = [];
		}
		$rewriteArray = array_merge($rewriteArray, $pathArray);
		$this->_setRewriteArray($rewriteArray);
		return $this;
	}

	/**
	 * concat the link
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return Link
	 */

	public function concat($optionArray = [])
	{
		$optionArray = array_merge(self::$_optionArray, $optionArray);
		$loader = new Asset\Loader(Registry::getInstance());
		$loader
			->init($this->_getCollectionArray())
			->concat($optionArray, $this->_getRewriteArray());

		/* update collection */

		$this->_setRewriteArray();
		$this->_setCollectionArray($loader->getCollectionArray());
		return $this;
	}

	/**
	 * render the link
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = null;

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('link');

		/* handle collection */

		$collectionArray = $this->_getCollectionArray();
		$collectionKeys = array_keys($collectionArray);
		$lastKey = end($collectionKeys);

		/* process collection */

		foreach ($collectionArray as $key => $attribute)
		{
			if ($attribute['href'])
			{
				$output .= $linkElement
					->copy()
					->attr($attribute);
				if ($key !== $lastKey)
				{
					$output .= PHP_EOL;
				}
			}
		}
		$this->clear();
		return $output;
	}

	/**
	 * get the rewrite array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _getRewriteArray()
	{
		return self::$_rewriteArray[self::$_namespace];
	}

	/**
	 * set the rewrite array
	 *
	 * @since 3.0.0
	 *
	 * @param array $rewriteArrayy
	 */

	protected function _setRewriteArray($rewriteArrayy = [])
	{
		self::$_rewriteArray[self::$_namespace] = $rewriteArrayy;
	}
}