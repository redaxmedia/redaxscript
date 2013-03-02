<?php

/**
 * analytics install
 */

function analytics_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Analytics\', \'analytics\', \'Redaxmedia\', \'Goggle Analytics tracking\', \'1.2.1\', 1, 0)';
	mysql_query($query);
}

/**
 * analytics uninstall
 */

function analytics_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'analytics\' LIMIT 1';
	mysql_query($query);
}
?>