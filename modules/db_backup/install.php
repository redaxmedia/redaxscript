<?php

/**
 * db backup install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function db_backup_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'DB backup\', \'db_backup\', \'Redaxmedia\', \'Creates full database backup\', \'2.0.0\', 1, 1)';
	mysql_query($query);
}

/**
 * db backup uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function db_backup_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'db_backup\' LIMIT 1';
	mysql_query($query);
}
?>
