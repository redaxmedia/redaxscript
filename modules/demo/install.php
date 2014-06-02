<?php

/**
 * demo install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function demo_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Demo\', \'demo\', \'Redaxmedia\', \'Enable anonymous login\', \'2.2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * demo uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function demo_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'demo\' LIMIT 1';
	mysql_query($query);
}
?>
