<?php
namespace Redaxscript;

/* bootstrap */

include_once('includes' . DIRECTORY_SEPARATOR . 'bootstrap.php');

/* header */

Header::init();

/* get instance */

$config = Config::getInstance();

/* restrict access */

if ($config->get('env') !== 'production')
{
	set_include_path('templates');
	include_once('install' . DIRECTORY_SEPARATOR . 'index.phtml');
}
else
{
	Header::responseCode(403);
	exit;
}