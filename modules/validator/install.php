<?php

/**
 * validator install
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function validator_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Validator\', \'validator\', \'Redaxmedia\', \'HTML validator for developers\', \'2.1.0\', 1, 1)';
	mysql_query($query);
}

/**
 * validator uninstall
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function validator_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'validator\' LIMIT 1';
	mysql_query($query);
}
?>
