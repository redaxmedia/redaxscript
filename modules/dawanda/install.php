<?php

/**
 * dawanda install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function dawanda_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Dawanda\', \'dawanda\', \'Redaxmedia\', \'Javascript powered Dawanda API client\', \'2.0.0\', 1, 0)';
	mysql_query($query);
}

/**
 * dawanda uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function dawanda_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'dawanda\' LIMIT 1';
	mysql_query($query);
}
?>