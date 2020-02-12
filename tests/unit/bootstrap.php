<?php
namespace Redaxscript;

use function error_reporting;
use function getenv;

error_reporting(getenv('COMPAT_MODE') ? E_WARNING | E_ERROR | E_PARSE : E_DEPRECATED | E_WARNING | E_ERROR | E_PARSE);

/* include */

include_once('includes' . DIRECTORY_SEPARATOR . 'Autoloader.php');
include_once('TestCaseAbstract.php');

/* autoload */

$autoloader = new Autoloader();
$autoloader->init();

/* get the instance */

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
