<?php

/**
 * fb group install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function fb_group_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Facebook group\', \'fb_group\', \'Redaxmedia\', \'Integrate a Facebook group\', \'2.2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * fb group uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function fb_group_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'fb_group\' LIMIT 1';
	mysql_query($query);
}
?>
