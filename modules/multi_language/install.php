<?php

/**
 * multi language install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function multi_language_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Multi language\', \'multi_language\', \'Redaxmedia\', \'Support for multiple languages\', \'2.0.0\', 1, 0)';
	mysql_query($query);
}

/**
 * multi language uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function multi_language_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'multi_language\' LIMIT 1';
	mysql_query($query);
}
?>