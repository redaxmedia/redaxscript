<?php

/* feed reader install */

function feed_reader_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Feed reader\', \'feed_reader\', \'Redaxmedia\', \'Read external RSS and Atom feeds\', \'1.1\', 1, 0)';
	mysql_query($query);
}

/* feed reader uninstall */

function feed_reader_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'feed_reader\' LIMIT 1';
	mysql_query($query);
}
?>