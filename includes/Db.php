<?php

/**
 * children class to handle the database
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Db
 * @author Henry Ruhs
 */

class Redaxscript_Db extends ORM
{
	/**
	 * connect to database
	 *
	 * @since 2.2.0
	 *
	 * @param Redaxscript_Registry $registry instance of the registry class
	 * @param Redaxscript_Config $config instance of the config class
	 */

	public static function connect(Redaxscript_Registry $registry, Redaxscript_Config $config)
	{
		/* try to connect */

		try
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
			self::configure(array(
				'return_result_sets', true,
				'caching', true
			));
			$registry->set('dbConnected', true);
			$registry->set('dbError', false);
		}

		/* handle exception */

		catch (PDOException $exception)
		{
			$registry->set('dbConnected', false);
			$registry->set('dbError', true);
		}
	}

	/**
	 * for table with prefix
	 *
	 * @since 2.2.0
	 *
	 * @param string $table name of the table
	 * @param string $connection which connection to use
	 *
	 * @return Redaxscript_Db
	 */

	public static function forPrefixTable($table = null, $connection = self::DEFAULT_CONNECTION)
	{
		self::_setup_db($connection);
		return new self(Redaxscript_Config::get('prefix') . $table, array(), $connection);
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
		return self::forPrefixTable('settings')->where('name', $key)->findOne()->value;
	}
}