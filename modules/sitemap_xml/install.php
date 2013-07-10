<?php

/**
 * sitemap xml install
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_xml_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Sitemap XML\', \'sitemap_xml\', \'Redaxmedia\', \'Generates a sitemap XML\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * sitemap xml uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_xml_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'sitemap_xml\' LIMIT 1';
	mysql_query($query);
}
?>