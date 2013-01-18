<?php

/**
 * seo tube install
 */

function seo_tube_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'SEO Tube\', \'seo_tube\', \'Ronny Springer & Henry Ruhs\', \'Load videos from youtube\', \'1.2.1\', 1, 0)';
	mysql_query($query);
}

/**
 * seo tube uninstall
 */

function seo_tube_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'seo_tube\' LIMIT 1';
	mysql_query($query);
}
?>