<?php

/* editor_install */

function editor_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Editor\', \'editor\', \'Redaxmedia\', \'Javascript powered WYSIWYG editor\', \'1.0\', 1, 0)';
	mysql_query($query);
}

/* editor_uninstall */

function editor_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'editor\' LIMIT 1';
	mysql_query($query);
}
?>