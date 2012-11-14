<?php

/**
 * sitemap install
 */

function sitemap_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Sitemap\', \'sitemap\', \'Redaxmedia\', \'Generates a sitemap tree\', \'1.1\', 1, 0)';
	mysql_query($query);
}

/**
 * sitemap uninstall
 */

function sitemap_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'sitemap\' LIMIT 1';
	mysql_query($query);
}
?>