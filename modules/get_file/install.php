<?php

/**
 * get file install
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function get_file_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Get file\', \'get_file\', \'Redaxmedia\', \'File information helper\', \'2.2.0\', 1, 0)';
	Redaxscript\Db::forPrefixTable('categories')->rawExecute($query);
}

/**
 * get file uninstall
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function get_file_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'get_file\' LIMIT 1';
	Redaxscript\Db::forPrefixTable('categories')->rawExecute($query);
}

