<?php

/**
 * live reload install
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function live_reload_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Live reload\', \'live_reload\', \'Redaxmedia\', \'Live reload for CSS\', \'2.1.0\', 1, 1)';
	mysql_query($query);
}

/**
 * live reload uninstall
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function live_reload_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'live_reload\' LIMIT 1';
	mysql_query($query);
}
?>
