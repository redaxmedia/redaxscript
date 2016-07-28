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
	 * path to config file
	 *
	 * @var string
	 */

	protected static $_configFile = 'config.php';

	/**
	 * array of the config
	 *
	 * @var array
	 */

	protected static $_configArray = [];

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $configFile file with config
	 */

	public static function init($configFile = null)
	{
		if (file_exists($configFile))
		{
			self::$_configFile = $configFile;
		}

		/* load config */

		$configArray = include(self::$_configFile);
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
		self::reset();
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
	 * @return boolean
	 */

	public static function write()
	{
		$configKeys = array_keys(self::$_configArray);
		$lastKey = end($configKeys);

		/* process config */

		$contents = '<?php' . PHP_EOL . 'return' . PHP_EOL . '[' . PHP_EOL;
		foreach (self::$_configArray as $key => $value)
		{
			if ($value)
			{
				$contents .= '	\'' . $key . '\' => \'' . $value . '\'';
			}
			else
			{
				$contents .= '	\'' . $key . '\' => null';
			}
			if ($key !== $lastKey)
			{
				$contents .= ',';
			}
			$contents .= PHP_EOL;
		}
		$contents .= '];';

		/* write to file */

		$output = file_put_contents(self::$_configFile, $contents) > 0;
		return $output;
	}

	/**
	 * reset the config
	 *
	 * @since 3.0.0
	 */

	public static function reset()
	{
		self::$_configArray = [];
	}
}
