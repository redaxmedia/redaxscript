<?php

/**
 * maps install
 */

function maps_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Maps\', \'maps\', \'Redaxmedia\', \'Integrates Goggle Maps\', \'1.2.1\', 1, 0)';
	mysql_query($query);
}

/**
 * maps uninstall
 */

function maps_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'maps\' LIMIT 1';
	mysql_query($query);
}
?>