<?php
namespace Redaxscript;

/* bootstrap */

include_once('includes' . DIRECTORY_SEPARATOR . 'bootstrap.php');

/* header */

header_remove('x-powered-by');

/* get instance */

$registry = Registry::getInstance();

/* redirect to install */

if ($registry->get('dbStatus') < 2 && is_file('install.php'))
{
	header('location: install.php');
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
	header('http/1.0 404 not found');
}

/* template */

$templateArray = Module\Hook::collect('renderTemplate');
if (is_array($templateArray) && array_key_exists('header', $templateArray))
{
	header($templateArray['header']);
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
