<?php
namespace Redaxscript;

use function array_key_exists;
use function array_keys;
use function basename;
use function dirname;
use function end;
use function is_array;
use function is_file;
use function parse_url;
use function str_replace;
use function trim;

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
	 * path to the config
	 *
	 * @var string
	 */

	protected static $_configPath = 'config.php';

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
	 * @param string $configPath path to the config
	 */

	public function init(string $configPath = null)
	{
		if (is_file($configPath))
		{
			self::$_configPath = $configPath;
		}

		/* load config */

		$configArray = include(self::$_configPath);
		if (is_array($configArray))
		{
			self::$_configArray = $configArray;
		}
	}

	/**
	 * get the value from config
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function get(string $key = null)
	{
		if (is_array(self::$_configArray) && array_key_exists($key, self::$_configArray))
		{
			return self::$_configArray[$key];
		}
		if (!$key)
		{
			return self::$_configArray;
		}
		return null;
	}

	/**
	 * set the value to config
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 */

	public function set(string $key = null, string $value = null)
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

	public function parse(string $dbUrl = null)
	{
		$dbUrl = parse_url($dbUrl);
		$this->clear();
		$this->set('dbType', str_replace('postgres', 'pgsql', $dbUrl['scheme']));
		$this->set('dbHost', $dbUrl['port'] ? $dbUrl['host'] . ':' . $dbUrl['port'] : $dbUrl['host']);
		$this->set('dbName', trim($dbUrl['path'], '/'));
		$this->set('dbUser', $dbUrl['user']);
		$this->set('dbPassword', $dbUrl['pass']);
	}

	/**
	 * write the config
	 *
	 * @since 2.4.0
	 *
	 * @return bool
	 */

	public function write() : bool
	{
		$configKeys = array_keys(self::$_configArray);
		$lastKey = end($configKeys);

		/* collect content */

		$content = '<?php' . PHP_EOL . 'return' . PHP_EOL . '[' . PHP_EOL;
		foreach (self::$_configArray as $key => $value)
		{
			if ($value)
			{
				$content .= '	\'' . $key . '\' => \'' . $value . '\'';
			}
			else
			{
				$content .= '	\'' . $key . '\' => null';
			}
			if ($key !== $lastKey)
			{
				$content .= ',';
			}
			$content .= PHP_EOL;
		}
		$content .= '];';

		/* write content */

		return $this->_writeContent($content);
	}

	/**
	 * clear the config
	 *
	 * @since 3.0.0
	 */

	public function clear()
	{
		self::$_configArray = [];
	}

	/**
	 * write content to file
	 *
	 * @since 2.4.0
	 *
	 * @param string|null $content
	 *
	 * @return bool
	 */

	protected function _writeContent(string $content = null) : bool
	{
		$filesystem = new Filesystem\File();
		$filesystem->init(dirname(self::$_configPath));
		return $filesystem->writeFile(basename (self::$_configPath), $content);
	}
}
