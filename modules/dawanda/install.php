<?php

/**
 * dawanda install
 */

function dawanda_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Dawanda\', \'dawanda\', \'Redaxmedia\', \'Javascript powered Dawanda client\', \'1.2\', 1, 0)';
	mysql_query($query);
}

/**
 * dawanda uninstall
 */

function dawanda_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'dawanda\' LIMIT 1';
	mysql_query($query);
}
?>