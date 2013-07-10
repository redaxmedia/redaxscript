<?php

/**
 * debugger install
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debugger_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Debugger\', \'debugger\', \'Redaxmedia\', \'Debug tool for developers\', \'2.0\', 1, 1)';
	mysql_query($query);
}

/**
 * debugger uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debugger_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'debugger\' LIMIT 1';
	mysql_query($query);
}
?>