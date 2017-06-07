<?php
namespace Redaxscript\Head;

use Redaxscript\Singleton;

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

	public function __toString()
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
	 * @return $this
	 */

	public function init($namespace = null)
	{
		self::$_namespace = get_called_class();
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
	 * @param string|array $attribute name or set of attributes
	 * @param string $value value of the attribute
	 *
	 * @return $this
	 */

	public function append($attribute = null, $value = null)
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
	 * @param string|array $attribute name or set of attributes
	 * @param string $value value of the attribute
	 *
	 * @return $this
	 */

	public function prepend($attribute = null, $value = null)
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
	 * @return $this
	 */

	public function remove($attribute = null, $value = null)
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
	 * @return $this
	 */

	public function clear()
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

	protected function _getCollectionArray()
	{
		$collectionArray = self::$_collectionArray[self::$_namespace];
		return is_array($collectionArray) ? $collectionArray : [];
	}

	/**
	 * set the collection array
	 *
	 * @since 3.0.0
	 *
	 * @param array $collectionArray
	 */

	protected function _setCollectionArray($collectionArray = [])
	{
		self::$_collectionArray[self::$_namespace] = $collectionArray;
	}
}