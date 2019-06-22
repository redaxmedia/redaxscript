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
$templateReplace = Module\Hook::trigger('templateReplace');
if ($templateReplace)
{
	echo $templateReplace;
}
else
{
	Module\Hook::trigger('templateStart');
	include_once($registry->get('template') . DIRECTORY_SEPARATOR . 'index.phtml');
	Module\Hook::trigger('templateEnd');
}
Module\Hook::trigger('renderEnd');
