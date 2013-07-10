<?php

/**
 * sitemap install
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Sitemap\', \'sitemap\', \'Redaxmedia\', \'Generates a sitemap tree\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * sitemap uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'sitemap\' LIMIT 1';
	mysql_query($query);
}
?>