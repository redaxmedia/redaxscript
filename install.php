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
	include_once('templates' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'index.phtml');
}
else
{
	Header::statusCode(403);
	exit;
}