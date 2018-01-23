<?php
namespace Redaxscript;

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
	exit;
}
if ($registry->get('contentError'))
{
	Header::statusCode(404);
}

/* template */

$templateArray = Module\Hook::collect('renderTemplate');
if (is_array($templateArray) && array_key_exists('header', $templateArray))
{
	Header::add($templateArray['header']);
}
if (is_array($templateArray) && array_key_exists('content', $templateArray))
{
	echo $templateArray['content'];
}
else
{
	include_once('templates' . DIRECTORY_SEPARATOR . $registry->get('template') . DIRECTORY_SEPARATOR . 'index.phtml');
}
Module\Hook::trigger('renderEnd');
