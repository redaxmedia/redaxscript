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

	function static connect($type = 'mysql')
	{
		$registry = Redaxscript_Registry::getInstance();

		/* try to connect */

		try
		{
			/* mysql */

			if ($type === 'mysql')
			{
				self::configure(
					'connection_string' => 'mysql:host=' . Redaxscript_Config::get('host') . ';dbname=' . Redaxscript_Config::get('name'),
					'username' => Redaxscript_Config::get('user'),
					'password', Redaxscript_Config::get('password'),
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
