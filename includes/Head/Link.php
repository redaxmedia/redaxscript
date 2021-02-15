<?php
namespace Redaxscript\Head;

use Redaxscript\Asset;
use Redaxscript\Html;
use Redaxscript\Registry;
use function array_key_exists;
use function array_merge;

/**
 * children class to create the link tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 * @author Balázs Szilágyi
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
		'lifetime' => 3600
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
	 * @param string|array $reference
	 *
	 * @return self
	 */

	public function appendFile($reference = null) : self
	{
		foreach ((array)$reference as $value)
		{
			$this->append(
			[
				'href' => $value,
				'rel' => 'stylesheet'
			]);
		}
		return $this;
	}

	/**
	 * prepend link file
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $reference
	 *
	 * @return self
	 */

	public function prependFile($reference = null) : self
	{
		foreach ((array)$reference as $value)
		{
			$this->prepend(
			[
				'href' => $value,
				'rel' => 'stylesheet'
			]);
		}
		return $this;
	}

	/**
	 * remove link file
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $reference
	 *
	 * @return self
	 */

	public function removeFile($reference = null) : self
	{
		foreach ((array)$reference as $value)
		{
			$this->remove('href', $value);
		}
		return $this;
	}

	/**
	 * rewrite the link
	 *
	 * @since 3.0.0
	 *
	 * @param array $pathArray
	 *
	 * @return self
	 */

	public function rewrite(array $pathArray = []) : self
	{
		$rewriteArray = $this->_getRewriteArray();
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
	 * @return self
	 */

	public function concat(array $optionArray = []) : self
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
	 * @return string|null
	 */

	public function render() : ?string
	{
		$output = null;

		/* html element */

		$linkElement = new Html\Element();
		$linkElement->init('link');

		/* handle collection */

		$collectionArray = $this->_getCollectionArray();

		/* process collection */

		foreach ($collectionArray as $attribute)
		{
			if (array_key_exists('href', $attribute))
			{
				$output .= $linkElement
					->copy()
					->attr($attribute);
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

	protected function _getRewriteArray() : array
	{
		return array_key_exists(self::$_namespace, self::$_rewriteArray) ? self::$_rewriteArray[self::$_namespace] : [];
	}

	/**
	 * set the rewrite array
	 *
	 * @since 3.0.0
	 *
	 * @param array $rewriteArray
	 */

	protected function _setRewriteArray(array $rewriteArray = []) : void
	{
		self::$_rewriteArray[self::$_namespace] = $rewriteArray;
	}
}
