<?php

/**
 * disqus install
 *
 * @since 2.0.0
 * @deprecated 2.1.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function disqus_install()
{
	$query = 'INSERT INTO ' . PREFIX . 'modules (name, alias, author, description, version, status, access) VALUES (\'Disqus\', \'disqus\', \'Redaxmedia\', \'Replace comments with disqus\', \'2.1.0\', 1, 0)';
	mysql_query($query);
}

/**
 * disqus uninstall
 *
 * @since 2.0.0
 * @deprecated 2.1.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function disqus_uninstall()
{
	$query = 'DELETE FROM ' . PREFIX . 'modules WHERE alias = \'disqus\' LIMIT 1';
	mysql_query($query);
}
?>
