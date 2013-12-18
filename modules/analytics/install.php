<?php

/**
 * analytics install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function analytics_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Analytics\', \'analytics\', \'Redaxmedia\', \'Integrates Goggle Analytics\', \'2.0.0\', 1, 0)';
	mysql_query($query);
}

/**
 * analytics uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function analytics_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'analytics\' LIMIT 1';
	mysql_query($query);
}
?>