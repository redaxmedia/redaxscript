<?php

/**
 * seo tube install
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function seo_tube_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'SEO Tube\', \'seo_tube\', \'Ronny Springer & Henry Ruhs\', \'Loads videos from youtube\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * seo tube uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function seo_tube_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'seo_tube\' LIMIT 1';
	mysql_query($query);
}
?>