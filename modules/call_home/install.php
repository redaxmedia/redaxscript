<?php

/**
 * call home install
 */

function call_home_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Call home\', \'call_home\', \'Redaxmedia\', \'Provides version and news updates\', \'1.3\', 1, 1)';
	mysql_query($query);
}

/**
 * call home uninstall
 */

function call_home_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'call_home\' LIMIT 1';
	mysql_query($query);
}
?>