<?php

/**
 * db backup install
 */

function db_backup_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'DB backup\', \'db_backup\', \'Redaxmedia\', \'Backup your database\', \'1.2.1\', 1, 1)';
	mysql_query($query);
}

/**
 * db backup uninstall
 */

function db_backup_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'db_backup\' LIMIT 1';
	mysql_query($query);
}
?>