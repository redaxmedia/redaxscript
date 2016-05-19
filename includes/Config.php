<?php
namespace Redaxscript;

/**
 * children class to store database config
 *
 * @since 2.4.0
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

	protected static $_configArray = array();

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $file file with config
	 */

	public static function init($file = 'config.php')
	{
		$configArray = include($file);
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
	 * @return mixed
	 */

	public static function get($key = null)
	{
		if (array_key_exists($key, self::$_configArray))
		{
			return self::$_configArray[$key];
		}
		else if (!$key)
		{
			return self::$_configArray;
		}
		return false;
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
	 * parse from database url
	 *
	 * @since 3.0.0
	 *
	 * @param string $dbUrl database url to be parsed
	 */

	public static function parse($dbUrl = null)
	{
		$dbUrl = parse_url($dbUrl);
		self::set('dbType', str_replace('postgres', 'pgsql', $dbUrl['scheme']));
		self::set('dbHost', $dbUrl['port'] ? $dbUrl['host'] . ':' . $dbUrl['port'] : $dbUrl['host']);
		self::set('dbName', trim($dbUrl['path'], '/'));
		self::set('dbUser', $dbUrl['user']);
		self::set('dbPassword', $dbUrl['pass']);
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
		$keys = array_keys(self::$_configArray);
		$lastKey = end($keys);

		/* process config */

		$contents = '<?php' . PHP_EOL;
		$contents .= 'return array(' . PHP_EOL;
		foreach (self::$_configArray as $key => $value)
		{
			$contents .= '	\'' . $key . '\' => \'' . $value . '\'';
			if ($key !== $lastKey)
			{
				$contents .= ',';
			}
			$contents .= PHP_EOL;
		}
		$contents .= ');';

		/* write to file */

		$output = file_put_contents($file, $contents) > 0 ? true : false;
		return $output;
	}
}
