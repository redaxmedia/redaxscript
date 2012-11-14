<?php

/**
 * debugger install
 */

function debugger_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Debugger\', \'debugger\', \'Redaxmedia\', \'Debug tool for developers\', \'1.1\', 1, 1)';
	mysql_query($query);
}

/**
 * debugger uninstall
 */

function debugger_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'debugger\' LIMIT 1';
	mysql_query($query);
}
?>