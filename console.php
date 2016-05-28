<?php
namespace Redaxscript;

use Redaxscript\Console;

error_reporting(0);

/* bootstrap */

include_once('includes/bootstrap.php');

/* command line */

if (php_sapi_name() === 'cli')
{
	$console = new Console\Console(Config::getInstance(), Request::getInstance());
	echo $console->init('cli');
}

/* else web */

else
{
	include_once('templates/console/console.phtml');
}