<?php

/**
 * archive install
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function archive_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Archive\', \'archive\', \'Redaxmedia\', \'Generates a archive tree\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * archive uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function archive_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'archive\' LIMIT 1';
	mysql_query($query);
}
?>