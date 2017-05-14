<?php
namespace Redaxscript;

/* bootstrap */

include_once('includes' . DIRECTORY_SEPARATOR . 'bootstrap.php');

/* header */

header_remove('x-powered-by');

/* get instance */

$config = Config::getInstance();

/* restrict access */

if ($config->get('env') !== 'production')
{
	include_once('templates' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'index.phtml');
}
else
{
	header('http/1.0 403 forbidden');
	exit;
}