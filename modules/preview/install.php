<?php

/**
 * preview install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function preview_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Preview\', \'preview\', \'Redaxmedia\', \'Preview template elements\', \'2.2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * preview uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function preview_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'preview\' LIMIT 1';
	mysql_query($query);
}

