<?php

/**
 * maps install
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function maps_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Maps\', \'maps\', \'Redaxmedia\', \'Integrate Goggle Maps\', \'2.2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * maps uninstall
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function maps_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'maps\' LIMIT 1';
	mysql_query($query);
}
?>
