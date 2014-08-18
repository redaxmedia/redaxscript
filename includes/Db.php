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
		/* mysql */

		if ($config::get('type') === 'mysql')
		{
			self::configure(array(
					'connection_string' => 'mysql:host=' . $config::get('host') . ';dbname=' . $config::get('name'),
					'username' => $config::get('user'),
					'password' => $config::get('password'),
					'driver_options' => array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
					)
				)
			);
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
	 * @return string
	 */

	public static function getSettings($key = null)
	{
		try
		{
			return self::forPrefixTable('settings')->where('name', $key)->findOne()->value;
		}
		// @codeCoverageIgnoreStart
		catch (\PDOException $exception)
		{
			return false;
		}
		// @codeCoverageIgnoreEnd
	}
}
