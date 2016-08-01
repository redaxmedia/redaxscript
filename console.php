<?php
namespace Redaxscript;

error_reporting(E_ERROR || E_PARSE);

/* bootstrap */

include_once('includes/bootstrap.php');

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$config = Config::getInstance();

/* command line */

if (php_sapi_name() === 'cli')
{
	$console = new Console\Console($registry, $request, $config);
	$output = $console->init('cli');
	if (is_string($output))
	{
		echo $output;
	}
}

/* ajax request */

else if ($request->getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest')
{
	$console = new Console\Console($registry, $request, $config);
	$output = $console->init('xhr');
	if (is_string($output))
	{
		echo htmlentities($output);
	}
}

/* else template */

else
{
	include_once('templates/console/console.phtml');
}