<?php
namespace Redaxscript;

/**
 * children class to provide the current language
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

	public function init($language = 'en')
	{
		$this->load('languages/en.json');

		/* merge language */

		if ($language !== 'en')
		{
			$this->load('languages/' . $language . '.json');
		}
	}

	/**
	 * get item from language
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string $index index of the array
	 *
	 * @return mixed
	 */

	public function get($key = null, $index = null)
	{
		/* handle index */

		if (array_key_exists($index, self::$_languageArray))
		{
			$languageArray = self::$_languageArray[$index];
		}
		else
		{
			$languageArray = self::$_languageArray;
		}

		/* values as needed */

		if (array_key_exists($key, $languageArray))
		{
			return $languageArray[$key];
		}
		else if (!$key)
		{
			return $languageArray;
		}
		return false;
	}

	/**
	 * set item to language
	 *
	 * @since 2.4.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function set($key = null, $value = null)
	{
		self::$_languageArray[$key] = $value;
	}

	/**
	 * load from language path
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $path single or multiple language path
	 */

	public function load($path = null)
	{
		$reader = new Reader();

		/* handle json */

		if (is_string($path))
		{
			$path =
			[
				$path
			];
		}

		/* load and merge files */

		foreach ($path as $file)
		{
			if (file_exists($file))
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
