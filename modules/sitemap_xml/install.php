<?php

/* sitemap xml install */

function sitemap_xml_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Sitemap XML\', \'sitemap_xml\', \'Redaxmedia\', \'Generates a sitemap XML\', \'1.1\', 1, 0)';
	mysql_query($query);
}

/* sitemap xml uninstall */

function sitemap_xml_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'sitemap_xml\' LIMIT 1';
	mysql_query($query);
}
?>