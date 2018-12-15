<?php
namespace Redaxscript;

use function is_file;
use function set_include_path;

/* bootstrap */

include_once('includes' . DIRECTORY_SEPARATOR . 'bootstrap.php');

/* header */

Header::init();

/* get instance */

$registry = Registry::getInstance();

/* redirect to install */

if ($registry->get('dbStatus') < 2 && is_file('install.php'))
{
	Header::doRedirect('install.php');
	exit;
}

/* render */

Module\Hook::trigger('renderStart');
if ($registry->get('renderBreak'))
{
	Header::responseCode(202);
	exit;
}

/* template */

set_include_path('templates');
$renderTemplate = Module\Hook::trigger('renderTemplate');
if ($renderTemplate)
{
	echo $renderTemplate;
}
else
{

	include_once($registry->get('template') . DIRECTORY_SEPARATOR . 'index.phtml');
}
Module\Hook::trigger('renderEnd');
