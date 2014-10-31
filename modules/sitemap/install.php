<?php

/**
 * sitemap install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Sitemap\', \'sitemap\', \'Redaxmedia\', \'Generate a sitemap tree\', \'2.2.0\', 1, 0)';
	Redaxscript\Db::forTablePrefix('categories')->rawExecute($query);
}

/**
 * sitemap uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function sitemap_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'sitemap\' LIMIT 1';
	Redaxscript\Db::forTablePrefix('categories')->rawExecute($query);
}

