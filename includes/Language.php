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
	 * array of language values
	 *
	 * @var array
	 */

	protected static $_values = array();

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 */

	public static function init($language = 'en')
	{
		self::load('languages/en.json');

		/* merge another language */

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

		if (isset($index) && isset(self::$_values[$index]))
		{
			$values = self::$_values[$index];
		}
		else
		{
			$values = self::$_values;
		}

		/* values as needed */

		if (is_null($key))
		{
			$output = $values;
		}
		else if (array_key_exists($key, $values))
		{
			$output = $values[$key];

			/* convert encoding */

			if (function_exists('mb_convert_encoding'))
			{
				$charset = Db::getSettings('charset');

				/* process if charset */

				if (isset($charset))
				{
					$output = mb_convert_encoding($values[$key], $charset, 'utf-8, latin1');
				}
			}
		}
		return $output;
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

		if (!is_array($json))
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
				$values = json_decode($contents, true);
				if (is_array($values))
				{
					self::$_values = array_merge(self::$_values, $values);
				}
			}
		}
	}
}
