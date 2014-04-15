<?php

/**
 * call home install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function call_home_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Call home\', \'call_home\', \'Redaxmedia\', \'Provide version and news updates\', \'2.1.0\', 1, 1)';
	mysql_query($query);
}

/**
 * call home uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function call_home_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'call_home\' LIMIT 1';
	mysql_query($query);
}
?>
