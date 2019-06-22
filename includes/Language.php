<?php
namespace Redaxscript;

use function array_key_exists;
use function array_merge;
use function is_array;
use function is_file;

/**
 * children class to provide the language
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Language
 * @author Henry Ruhs
 */

class Language extends Singleton
{
	/**
	 * array of the language
	 *
	 * @var array
	 */

	protected static $_languageArray = [];

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 *
	 * @param string $language detected language to process
	 */

	public function init(string $language = 'en') : void
	{
		$this->load('languages/en.json');

		/* merge language */

		if ($language !== 'en')
		{
			$this->load('languages/' . $language . '.json');
		}
	}

	/**
	 * get the value from language
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function get(string $key = null)
	{
		if (is_array(self::$_languageArray) && array_key_exists($key, self::$_languageArray))
		{
			return self::$_languageArray[$key];
		}
		return null;
	}

	/**
	 * get the array from language
	 *
	 * @since 4.0.0
	 *
	 * @return string|array|null
	 */

	public function getArray() : array
	{
		return self::$_languageArray;
	}

	/**
	 * set the value to language
	 *
	 * @since 2.4.0
	 *
	 * @param string $key key of the item
	 * @param string|array $value value of the item
	 */

	public function set(string $key = null, $value = null) : void
	{
		self::$_languageArray[$key] = $value;
	}

	/**
	 * load from language path
	 *
	 * @since 3.0.0
	 *
	 * @param string|array $path single or multiple language path
	 */

	public function load($path = null) : void
	{
		$reader = new Reader();
		$reader->init();

		/* process path */

		foreach ((array)$path as $file)
		{
			if (is_file($file))
			{
				$languageArray = $reader->loadJSON($file)->getArray();
				if (is_array($languageArray))
				{
					self::$_languageArray = array_merge(self::$_languageArray, $languageArray);
				}
			}
		}
	}
}
