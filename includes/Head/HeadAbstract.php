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
	 * @var string
	 */

	protected static $_collectionArray;

	/**
	 * stringify the collection
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->render();
	}

	/**
	 * append to the collection
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $attribute name or set of attributes
	 * @param string $value value of the attribute
	 *
	 * @return Head
	 */

	public function append($attribute = null, $value = null)
	{
		// basicly a copy of attr() from html\element - this code is untested but should work using append('src', 'scripts/foo.js') or with and array of attributes;
		if (is_array($attribute))
		{
			self::$_collectionArray = array_merge(self::$_collectionArray, array_map('trim', $attribute));
		}
		else if (strlen($attribute) && strlen($value))
		{
			self::$_collectionArray[$attribute] = trim($value);
		}
		return self;
	}

	/**
	 * prepend to the collection
	 *
	 * @since 3.0.0
	 *
	 * @return Head
	 */

	public function prepend()
	{
		// we can do this later once append() is working
		return self;
	}

	/**
	 * clean the collection
	 *
	 * @since 3.0.0
	 *
	 * @return Head
	 */

	public function clean()
	{
		self::$_collectionArray = [];
		return self;
	}
}