<?php
namespace Redaxscript;

error_reporting(0);

/* include files */

include_once('includes/contents.php');
include_once('includes/generate.php');
include_once('includes/head.php');
include_once('includes/loader.php');
include_once('includes/migrate.php');
include_once('includes/navigation.php');
include_once('includes/query.php');
include_once('includes/router.php');
include_once('includes/search.php');
include_once('includes/startup.php');
include_once('includes/comments.php');
include_once('includes/login.php');
include_once('includes/password.php');
include_once('includes/reminder.php');
include_once('includes/password.php');
include_once('includes/registration.php');

/* bootstrap */

include_once('includes/bootstrap.php');

/* get instance */

$registry = Registry::getInstance();

/* redirect to install */

if ($registry->get('dbStatus') < 2 && file_exists('install.php'))
{
	echo '<meta http-equiv="refresh" content="2; url=install.php" />' . PHP_EOL;
}

/* include admin files */

if ($registry->get('loggedIn') === $registry->get('token'))
{
	include_once('includes/admin_admin.php');
	include_once('includes/admin_contents.php');
	include_once('includes/admin_groups.php');
	include_once('includes/admin_modules.php');
	include_once('includes/admin_query.php');
	include_once('includes/admin_router.php');
	include_once('includes/admin_settings.php');
	include_once('includes/admin_users.php');
}

/* trigger init */

Hook::trigger('init');

/* assets loader */

if ($registry->get('firstParameter') === 'loader' && ($registry->get('secondParameter') === 'styles' || $registry->get('secondParameter') === 'scripts'))
{
	echo loader($registry->get('secondParameter'), 'outline');
}

/* else render template */

else
{
	Hook::trigger('renderStart');
	if ($registry->get('renderBreak'))
	{
		return;
	}
	if ($registry->get('routerBreak'))
	{
		$registry->set('contentError', false);
	}
	if ($registry->get('contentError'))
	{
		header('http/1.0 404 not found');
	}
	include_once('templates/' . $registry->get('template') . '/index.phtml');
	Hook::trigger('renderEnd');
}
