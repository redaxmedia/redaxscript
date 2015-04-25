<?php
namespace Redaxscript;

/**
 * parent class to store database config
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Config
 * @author Henry Ruhs
 */

class Config extends Singleton
{
	/**
	 * array of the config
	 *
	 * @var array
	 */

	private static $_configArray = array();

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $file file with config
	 */

	public static function init($file = 'config.php')
	{
		$contents = file_get_contents($file);
		$configArray= json_decode($contents, true);
		if (is_array($configArray))
		{
			self::$_configArray = $configArray;
		}
	}

	/**
	 * get item from config
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array
	 */

	public static function get($key = null)
	{
		$output = false;

		/* values as needed */

		if (is_null($key))
		{
			$output = self::$_configArray;
		}
		else if (getenv($key))
		{
			$output = getenv($key);
		}
		else if (array_key_exists($key, self::$_configArray))
		{
			$output = self::$_configArray[$key];
		}
		return $output;
	}

	/**
	 * set item to config
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 */

	public static function set($key = null, $value = null)
	{
		self::$_configArray[$key] = $value;
	}

	/**
	 * write config to file
	 *
	 * @since 2.4.0
	 *
	 * @param string $file file with config
	 *
	 * @return boolean
	 */

	public static function write($file = 'config.php')
	{
		$configKeys = array_keys(self::$_configArray);
		$lastKey = end($configKeys);

		/* process config */

		$contents = '{' . PHP_EOL;
		foreach (self::$_configArray as $key => $value)
		{
			$contents .= '	"' . $key . '": "' . $value . '"';
			if ($key !== $lastKey)
			{
				$contents .= ',';
			}
			$contents .= PHP_EOL;
		}
		$contents .= '}';

		/* write to file */

		$output = file_put_contents($file, $contents) > 0 ? true : false;
		return $output;
	}
}
