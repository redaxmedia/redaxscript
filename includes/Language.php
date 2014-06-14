<?php

/**
 * parent class to provide language
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Language
 * @author Henry Ruhs
 */

class Redaxscript_Language
{
	/**
	 * instance of the class
	 *
	 * @var object
	 */

	protected static $_instance = null;

	/**
	 * array of language values
	 *
	 * @var array
	 */

	protected static $_values = array();

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 */

	public function __construct()
	{
	}

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
	 *
	 * @return string
	 */

	public static function get($key = null)
	{
		if (array_key_exists($key, self::$_values))
		{
			if (function_exists('mb_convert_encoding'))
			{
				$output = mb_convert_encoding(self::$_values[$key], s('charset'), 'utf-8, latin1');
			}
			else
			{
				$output = self::$_values[$key];
			}
		}
		else
		{
			$output = null;
		}
		return $output;
	}

	/**
	 * load from language files
	 *
	 * @since 2.2.0
	 *
	 * @param string|array $ single or multiple language files
	 */

	public function load($json = null)
	{
		if (!is_array($json))
		{
			$json = array($json);
		}

		/* merge language files */

		foreach ($json as $file)
		{
			$values = json_decode(file_get_contents($file), true);
			if (is_array($values))
			{
				self::$_values = array_merge(self::$_values, $values);
			}
		}
	}

	/**
	 * instance of the class
	 *
	 * @since 2.2.0
	 *
	 * @return object
	 */

	public static function instance()
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * reset the instance
	 *
	 * @since 2.2.0
	 *
	 * @return object
	 */

	public static function reset()
	{
		self::$_instance = null;
	}
}