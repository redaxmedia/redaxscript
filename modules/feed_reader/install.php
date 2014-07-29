<?php

/**
 * feed reader install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function feed_reader_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Feed reader\', \'feed_reader\', \'Redaxmedia\', \'Read external RSS and Atom feeds\', \'2.2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * feed reader uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function feed_reader_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'feed_reader\' LIMIT 1';
	mysql_query($query);
}

