<?php

/**
 * file manager install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function file_manager_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'File manager\', \'file_manager\', \'Redaxmedia\', \'Simple file management\', \'2.2.0\', 1, 1)';
	mysql_query($query);
}

/**
 * file manager uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function file_manager_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'file_manager\' LIMIT 1';
	mysql_query($query);
}

