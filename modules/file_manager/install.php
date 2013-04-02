<?php

/**
 * file manager install
 */

function file_manager_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'File manager\', \'file_manager\', \'Redaxmedia\', \'Simple file management\', \'1.3\', 1, 1)';
	mysql_query($query);
}

/**
 * file manager uninstall
 */

function file_manager_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'file_manager\' LIMIT 1';
	mysql_query($query);
}
?>