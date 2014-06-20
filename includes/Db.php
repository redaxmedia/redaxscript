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
