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
		return '<!-- ' . get_called_class() . ' === null -->';
	}

	/**
	 * append to the collection
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $attribute name or set of attributes
	 * @param string $value value of the attribute
	 *
	 * @return HeadAbstract
	 */

	public function append($attribute = null, $value = null)
	{
		if (is_array($attribute))
		{
			self::$_collectionArray[] = array_map('trim', $attribute);
		}
		else if (strlen($attribute) && strlen($value))
		{
			self::$_collectionArray[] =
			[
				trim($attribute) => trim($value)
			];
		}
		return $this;
	}

	/**
	 * prepend to the collection
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $attribute name or set of attributes
	 * @param string $value value of the attribute
	 *
	 * @return HeadAbstract
	 */

	public function prepend($attribute = null, $value = null)
	{
		if (is_array($attribute))
		{
			array_unshift(self::$_collectionArray, array_map('trim', $attribute));
		}
		else if (strlen($attribute) && strlen($value))
		{
			array_unshift(self::$_collectionArray,
			[
				trim($attribute) => trim($value)
			]);
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
		/*@todo: this is clearing all collections and therefore needs refactoring
		/*@todo: maybe a namespaced collection or correct use of self::$_collectionArray vs. static::$_collectionArray */
		self::$_collectionArray = [];
		return $this;
	}
}