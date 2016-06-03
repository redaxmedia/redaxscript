<?php
namespace Redaxscript;

use Redaxscript\Console;

error_reporting(E_ERROR || E_PARSE);

/* bootstrap */

include_once('includes/bootstrap.php');

/* get instance */

$config = Config::getInstance();
$request =  Request::getInstance();

/* command line */

if (php_sapi_name() === 'cli')
{
	$console = new Console\Console($config, $request);
	echo $console->init('cli');
}

/* ajax request */

else if ($request->getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest')
{
	$console = new Console\Console($config, $request);
	echo $console->init('ajax');
}

/* else web */

else
{
	include_once('templates/console/console.phtml');
}