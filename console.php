<?php
namespace Redaxscript;

use Redaxscript\Console;

error_reporting(E_ERROR || E_PARSE);

/* bootstrap */

include_once('includes/bootstrap.php');

/* command line */

if (php_sapi_name() === 'cli')
{
	$console = new Console\Console(Config::getInstance(), Request::getInstance());
	echo $console->init();
}

/* else web */

else
{
	include_once('templates/console/console.phtml');
}