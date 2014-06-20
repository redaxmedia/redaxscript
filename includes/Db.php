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
	function static connect($type = 'mysql')
	{
		/* handle mysql */

		if ($type === 'mysql')
		{
			self::configure('mysql:host=' . $host . ';dbname=' . $name);
			self::configure('username', $user);
			self::configure('password', $password);
			self::configure('driver_options', array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
			));
		}
		
		/* register database */
		
		//$_SESSION[ROOT . '/db_connected'] = 1;
		//$_SESSION[ROOT . '/db_error'] = '';
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
