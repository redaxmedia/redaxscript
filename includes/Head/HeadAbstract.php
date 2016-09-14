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

	protected static $_namespace = null;

	/**
	 * collection of the head
	 *
	 * @var array
	 */

	protected static $_collectionArray = [];

	/**
	 * init the class
	 *
	 * @param string $namespace collection sub namespace
	 *
	 * @since 3.0.0
	 *
	 * @return HeadAbstract
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
	/*@todo: we need to find a fallback if someone did not use the init method */

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
		return '<!-- ' . self::$_namespace . ' === null -->';
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
			self::$_collectionArray[self::$_namespace][] = array_map('trim', $attribute);
		}
		else if (strlen($attribute) && strlen($value))
		{
			self::$_collectionArray[self::$_namespace][] =
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
			array_unshift(self::$_collectionArray[self::$_namespace], array_map('trim', $attribute));
		}
		else if (strlen($attribute) && strlen($value))
		{
			array_unshift(self::$_collectionArray[self::$_namespace],
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
		self::$_collectionArray[self::$_namespace] = [];
		return $this;
	}
}