<?php

/**
 * debug install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debug_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Debug\', \'debug\', \'Redaxmedia\', \'Debug tool for developers\', \'2.2.0\', 1, 1)';
	mysql_query($query);
}

/**
 * debug uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debug_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'debug\' LIMIT 1';
	mysql_query($query);
}
?>
