<?php

/**
 * archive install
 */

function archive_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Archive\', \'archive\', \'Redaxmedia\', \'Generates a archive tree\', \'1.3\', 1, 0)';
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