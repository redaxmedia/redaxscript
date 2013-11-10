<?php

/**
 * contact install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Contact\', \'contact\', \'Redaxmedia\', \'Simple contact form\', \'2.0.0\', 1, 0)';
	mysql_query($query);
}

/**
 * contact uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function contact_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'contact\' LIMIT 1';
	mysql_query($query);
}
?>