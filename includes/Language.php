<?php
namespace Redaxscript;

/**
 * parent class to provide the current language
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

	protected static $_languageArray = array();

	/**
	 * init the class
	 *
	 * @param string $language detected language to process
	 *
	 * @since 2.2.0
	 */

	public static function init($language = 'en')
	{
		self::load('languages/en.json');

		/* merge language */

		if ($language !== 'en')
		{
			self::load('languages/' . $language . '.json');
		}
	}

	/**
	 * get item from language
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $index index of the key array
	 *
	 * @return mixed
	 */

	public static function get($key = null, $index = null)
	{
		$output = false;

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

		if (!$key)
		{
			$output = $languageArray;
		}
		else if (array_key_exists($key, $languageArray))
		{
			$output = $languageArray[$key];
		}
		return $output;
	}

	/**
	 * set item to language
	 *
	 * @since 2.4.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function set($key = null, $value = null)
	{
		self::$_languageArray[$key] = $value;
	}

	/**
	 * load from language files
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $json single or multiple language paths
	 */

	public static function load($json = null)
	{
		/* handle json */

		if (is_string($json))
		{
			$json = array(
				$json
			);
		}

		/* merge language files */

		foreach ($json as $file)
		{
			if (file_exists($file))
			{
				$contents = file_get_contents($file);
				$languageArray = json_decode($contents, true);
				if (is_array($languageArray))
				{
					self::$_languageArray = array_merge(self::$_languageArray, $languageArray);
				}
			}
		}
	}
}
