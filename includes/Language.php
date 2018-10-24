<?php
namespace Redaxscript;

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

	public function init(string $language = 'en')
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
	 * @param string $index index of the array
	 *
	 * @return string|array|null
	 */

	public function get(string $key = null, string $index = null)
	{
		/* handle index */

		if (is_array(self::$_languageArray) && array_key_exists($index, self::$_languageArray))
		{
			$languageArray = self::$_languageArray[$index];
		}
		else
		{
			$languageArray = self::$_languageArray;
		}

		/* values as needed */

		if (is_array($languageArray) && array_key_exists($key, $languageArray))
		{
			return $languageArray[$key];
		}
		else if (!$key)
		{
			return $languageArray;
		}
		return null;
	}

	/**
	 * set the value to language
	 *
	 * @since 2.4.0
	 *
	 * @param string $key key of the item
	 * @param string|array $value value of the item
	 */

	public function set(string $key = null, $value = null)
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

	public function load($path = null)
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
