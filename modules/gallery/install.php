<?php

/**
 * gallery install
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function gallery_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Gallery\', \'gallery\', \'Redaxmedia\', \'Lightbox enhanced image gallery\', \'2.2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * gallery uninstall
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function gallery_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'gallery\' LIMIT 1';
	mysql_query($query);
}
