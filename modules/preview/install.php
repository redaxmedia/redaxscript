<?php

/**
 * preview install
 */

function preview_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Preview\', \'preview\', \'Redaxmedia\', \'Previews current template\', \'1.2.1\', 1, 0)';
	mysql_query($query);
}

/**
 * preview uninstall
 */

function preview_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'preview\' LIMIT 1';
	mysql_query($query);
}
?>