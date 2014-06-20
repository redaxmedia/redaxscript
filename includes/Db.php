<?php

/**
 * children class to handle database
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
		/* handle mysql */

		if ($type === 'mysql')
		{
			self::configure('mysql:host=' . Redaxscript_Config::get('host') . ';dbname=' . Redaxscript_Config::get('name'));
			self::configure('username', Redaxscript_Config::get('user'));
			self::configure('password', Redaxscript_Config::get('password'));
			self::configure('driver_options', array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
			));
		}
		
		/* register database */
		
		$registry = Redaxscript_Registry::getInstance();
		$registry->set('dbConnected', true);
		$registry->set('dbError', false);
	}

        /**
	 * table with prefix
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
