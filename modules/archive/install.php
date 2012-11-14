<?php

/**
 * archive install
 */

function archive_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Archive\', \'archive\', \'Redaxmedia\', \'Displays a tree list of the past months\', \'1.1\', 1, 0)';
	mysql_query($query);
}

/**
 * archive uninstall
 */

function archive_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'archive\' LIMIT 1';
	mysql_query($query);
}
?>