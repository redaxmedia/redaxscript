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
	 * @param string $type database type
	 */

	public static function connect($type = 'mysql', Redaxscript_Config $config)
	{
		$registry = Redaxscript_Registry::getInstance();

		/* try to connect */

		try
		{
			/* mysql */

			if ($type === 'mysql')
			{
				self::configure(
					'connection_string' => 'mysql:host=' . $config::get('host') . ';dbname=' . $config::get('name'),
					'username' => $config::get('user'),
					'password', $config::get('password'),
					'driver_options', array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
					)
				);
			}
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
	 * @param string $table_name name of the table
	 * @param string $connection_name which connection to use
	 *
	 * @return ORM
	 */

	public static function for_prefix_table($table_name = null, $connection_name = self::DEFAULT_CONNECTION)
	{
		self::_setup_db($connection_name);
		return new self(Redaxscript_Config::get('prefix') . $table_name, array(), $connection_name);
	}
}
