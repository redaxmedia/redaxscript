<?php

/**
 * feed generator install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function feed_generator_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Feed generator\', \'feed_generator\', \'Redaxmedia\', \'Generate Atom feeds from content\', \'2.2.0\', 1, 0)';
	Redaxscript\Db::rawExecute($query);
}

/**
 * feed generator uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function feed_generator_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'feed_generator\' LIMIT 1';
	Redaxscript\Db::rawExecute($query);
}

