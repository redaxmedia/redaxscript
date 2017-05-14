<?php
namespace Redaxscript;

error_reporting(E_ERROR | E_PARSE);

/* autoload */

include_once('includes' . DIRECTORY_SEPARATOR . 'Autoloader.php');
include_once('BenchCaseAbstract.php');

/* init */

$autoloader = new Autoloader();
$autoloader->init();

/* get instance */

$config = Config::getInstance();

/* config */

$dbUrl = getenv('DB_URL');
if ($dbUrl)
{
	$config->parse($dbUrl);
}
else
{
	$config->set('dbType', 'sqlite');
	$config->set('dbHost', ':memory:');
}
$config->set('dbPrefix', 'test_');

/* database */

Db::construct($config);
Db::init();

/* language */

$language = Language::getInstance();
$language->init();