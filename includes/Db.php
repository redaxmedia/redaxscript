<?php
namespace Redaxscript;

use ORM;
use PDO;

/**
 * children class to handle the database
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Db
 * @author Henry Ruhs
 *
 * @method _setupDb()
 * @method deleteMany()
 * @method deleteOne()
 * @method findMany()
 * @method findOne()
 */

class Db extends ORM
{
	/**
	 * connect to database
	 *
	 * @since 2.2.0
	 *
	 * @param Config $config instance of the config class
	 */

	public static function init(Config $config)
	{
		/* sqlite */

		if ($config::get('type') === 'sqlite')
		{
			self::configure('sqlite:' . $config::get('file'));
		}

		/* mysql and pgsql */

		if ($config::get('type') === 'mysql' || $config::get('type') === 'pgsql')
		{
			self::configure(array(
				'connection_string' => $config::get('type') . ':host=' . $config::get('host') . ';dbname=' . $config::get('name') . ';charset=utf8',
				'username' => $config::get('user'),
				'password' => $config::get('password')
			));
		}

		/* general */

		self::configure(array(
			'caching' => true,
			'caching_auto_clear' => true,
			'return_result_sets' => true
		));
	}

	/**
	 * for table with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string $connection which connection to use
	 *
	 * @return Db
	 */

	public static function forPrefixTable($table = null, $connection = self::DEFAULT_CONNECTION)
	{
		self::_setupDb($connection);
		return new self(Config::get('prefix') . $table, array(), $connection);
	}

	/**
	 * get item from settings
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return mixed
	 */

	public static function getSettings($key = null)
	{
		return self::forPrefixTable('settings')->where('name', $key)->findOne()->value;
	}
}
