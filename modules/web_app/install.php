<?php

/**
 * web app install
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function web_app_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Web app\', \'web_app\', \'Redaxmedia\', \'Web application support\', \'2.1.0\', 1, 0)';
	mysql_query($query);
}

/**
 * web app uninstall
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function web_app_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'web_app\' LIMIT 1';
	mysql_query($query);
}
?>
