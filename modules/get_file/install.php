<?php

/**
 * get file install
 */

function get_file_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Get file\', \'get_file\', \'Redaxmedia\', \'File information helper\', \'1.1\', 1, 0)';
	mysql_query($query);
}

/**
 * get file uninstall
 */

function get_file_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'get_file\' LIMIT 1';
	mysql_query($query);
}
?>