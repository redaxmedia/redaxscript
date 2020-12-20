<?php
namespace Redaxscript;

use function htmlentities;
use function php_sapi_name;
use function set_include_path;
use function strlen;

/* bootstrap */

include_once('includes' . DIRECTORY_SEPARATOR . 'bootstrap.php');

/* header */

Header::init();

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$language = Language::getInstance();
$config = Config::getInstance();

/* command line */

if (php_sapi_name() === 'cli')
{
	$console = new Console\Console($registry, $request, $language, $config);
	$output = $console->init('cli');
	if (strlen($output))
	{
		echo $output;
	}
}

/* http request */

else if ($request->getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest')
{
	$console = new Console\Console($registry, $request, $language, $config);
	$output = $console->init('template');
	if (strlen($output))
	{
		Header::responseCode(200);
		echo htmlentities($output, ENT_QUOTES);
	}
	else
	{
		Header::responseCode(404);
	}
}

/* else template */

else
{
	set_include_path('templates');
	include_once('console' . DIRECTORY_SEPARATOR . 'index.phtml');
}
