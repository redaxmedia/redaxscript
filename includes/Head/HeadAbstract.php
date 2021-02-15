<?php
namespace Redaxscript\Head;

use Redaxscript\Singleton;
use function array_key_exists;
use function array_map;
use function array_unshift;
use function is_array;
use function strlen;
use function trim;
use function ucfirst;

/**
 * abstract class to create a head class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 */

abstract class HeadAbstract extends Singleton implements HeadInterface
{
	/**
	 * collection namespace
	 *
	 * @var string
	 */

	protected static $_namespace = 'Redaxscript\Head';

	/**
	 * collection of the head
	 *
	 * @var array
	 */

	protected static $_collectionArray = [];

	/**
	 * stringify the collection
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString() : string
	{
		$render = $this->render();
		if ($render)
		{
			return $render;
		}
		return '<!-- ' . self::$_namespace . ' -->';
	}

	/**
	 * init the class
	 *
	 * @param string $namespace collection sub namespace
	 *
	 * @since 3.0.0
	 *
	 * @return self
	 */

	public function init(string $namespace = null) : self
	{
		self::$_namespace = static::class;
		if ($namespace)
		{
			self::$_namespace .= '\\' . ucfirst($namespace);
		}
		return $this;
	}

	/**
	 * append to the collection
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $attribute key or array of attributes
	 * @param string $value value of the attribute
	 *
	 * @return self
	 */

	public function append($attribute = null, string $value = null) : self
	{
		$collectionArray = $this->_getCollectionArray();
		if (is_array($attribute))
		{
			$collectionArray[] = array_map('trim', $attribute);
		}
		else if (strlen($attribute) && strlen($value))
		{
			$collectionArray[] =
			[
				trim($attribute) => trim($value)
			];
		}
		$this->_setCollectionArray($collectionArray);
		return $this;
	}

	/**
	 * prepend to the collection
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $attribute key or array of attributes
	 * @param string $value value of the attribute
	 *
	 * @return self
	 */

	public function prepend($attribute = null, string $value = null) : self
	{
		$collectionArray = $this->_getCollectionArray();
		if (is_array($attribute))
		{
			array_unshift($collectionArray, array_map('trim', $attribute));
		}
		else if (strlen($attribute) && strlen($value))
		{
			array_unshift($collectionArray,
			[
				trim($attribute) => trim($value)
			]);
		}
		$this->_setCollectionArray($collectionArray);
		return $this;
	}

	/**
	 * remove from to the collection
	 *
	 * @since 3.0.0
	 *
	 * @param string $attribute name of attribute
	 * @param string $value value of the attribute
	 *
	 * @return self
	 */

	public function remove(string $attribute = null, string $value = null) : self
	{
		$collectionArray = $this->_getCollectionArray();
		if (is_array($collectionArray))
		{
			foreach ($collectionArray as $collectionKey => $collectionValue)
			{
				if ($collectionValue[$attribute] === $value)
				{
					unset($collectionArray[$collectionKey]);
				}
			}
			$this->_setCollectionArray($collectionArray);
		}
		return $this;
	}

	/**
	 * clear the collection
	 *
	 * @since 3.0.0
	 *
	 * @return self
	 */

	public function clear() : self
	{
		$this->_setCollectionArray();
		return $this;
	}

	/**
	 * get the collection array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _getCollectionArray() : array
	{
		return array_key_exists(self::$_namespace, self::$_collectionArray) ? self::$_collectionArray[self::$_namespace] : [];
	}

	/**
	 * set the collection array
	 *
	 * @since 3.0.0
	 *
	 * @param array $collectionArray
	 */

	protected function _setCollectionArray(array $collectionArray = []) : void
	{
		self::$_collectionArray[self::$_namespace] = $collectionArray;
	}
}
