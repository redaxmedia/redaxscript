<?php

/**
 * lazy load install
 *
 * @since 2.0.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function lazy_load_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Lazy load\', \'lazy_load\', \'Redaxmedia\', \'Lazy loads and multiserves images\', \'2.0.1\', 1, 0)';
	mysql_query($query);
}

/**
 * lazy load uninstall
 *
 * @since 2.0.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function lazy_load_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'lazy_load\' LIMIT 1';
	mysql_query($query);
}
?>