<?php

/**
 * recent view install
 */

function recent_view_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Recent view\', \'recent_view\', \'Redaxmedia\', \'Generates a list of recent views\', \'1.2\', 1, 0)';
	mysql_query($query);
}

/**
 * recent view uninstall
 */

function recent_view_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'recent_view\' LIMIT 1';
	mysql_query($query);
}
?>