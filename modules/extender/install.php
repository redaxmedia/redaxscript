<?php

/* extender install */

function extender_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Extender\', \'extender\', \'Redaxmedia\', \'Extend CSS and Javascript\', \'1.1\', 1, 0)';
	mysql_query($query);
}

/* extender uninstall */

function extender_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'extender\' LIMIT 1';
	mysql_query($query);
}
?>