<?php

/**
 * feed generator install
 */

function feed_generator_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Feed generator\', \'feed_generator\', \'Redaxmedia\', \'Generates Atom feeds from content\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * feed generator uninstall
 */

function feed_generator_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'feed_generator\' LIMIT 1';
	mysql_query($query);
}
?>