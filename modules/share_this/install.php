<?php

/**
 * share this install
 */

function share_this_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Share this\', \'share_this\', \'Redaxmedia\', \'Integrates share buttons\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/**
 * share this uninstall
 */

function share_this_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'share_this\' LIMIT 1';
	mysql_query($query);
}
?>