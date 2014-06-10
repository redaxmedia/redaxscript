<?php

/**
 * qunit install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function qunit_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'QUnit\', \'qunit\', \'Redaxmedia\', \'Javascript unit testing\', \'2.2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * qunit uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function qunit_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'qunit\' LIMIT 1';
	mysql_query($query);
}

