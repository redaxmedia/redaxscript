<?php
namespace Redaxscript;

error_reporting(E_ERROR || E_PARSE);

/* bootstrap */

include_once('includes/bootstrap.php');

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$language = Language::getInstance();
$config = Config::getInstance();
$accessValidator = new Validator\Access();

/* command line */

if (php_sapi_name() === 'cli')
{
	$console = new Console\Console($registry, $request, $language, $config);
	$output = $console->init('cli');
	if (is_string($output))
	{
		echo $output;
	}
}

/* restrict access */

else if ($config->get('env') !== 'production' || $accessValidator->validate('1', $registry->get('myGroups')) === Validator\ValidatorInterface::PASSED)
{
	/* ajax request */

	if ($request->getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest')
	{
		$console = new Console\Console($registry, $request, $language, $config);
		$output = $console->init('xhr');
		if (is_string($output))
		{
			echo htmlentities($output);
		}
	}

	/* else template */

	else
	{
		include_once('templates/console/index.phtml');
	}
}
else
{
	header('http/1.0 403 forbidden');
	exit;
}