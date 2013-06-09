<?php

/**
 * qunit install
 */

function qunit_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'QUnit\', \'qunit\', \'Redaxmedia\', \'Javascript unit testing\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * qunit uninstall
 */

function qunit_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'qunit\' LIMIT 1';
	mysql_query($query);
}
?>