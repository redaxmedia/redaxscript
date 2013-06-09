<?php

/* contact install */

function contact_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Contact\', \'contact\', \'Redaxmedia\', \'Simple contact form\', \'2.0\', 1, 0)';
	mysql_query($query);
}

/* contact uninstall */

function contact_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'contact\' LIMIT 1';
	mysql_query($query);
}
?>