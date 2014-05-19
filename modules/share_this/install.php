<?php

/**
 * share this install
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function share_this_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author,
	description, version, status, access) VALUES (\'Share this\', \'share_this\', \'Redaxmedia\', \'Integrate social buttons\', \'2.1.0\', 1, 0)';
	mysql_query($query);
}

/**
 * share this uninstall
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function share_this_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'share_this\' LIMIT 1';
	mysql_query($query);
}
?>
