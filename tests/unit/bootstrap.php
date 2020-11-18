<?php
namespace Redaxscript;

use function error_reporting;
use function getenv;
use function ini_set;
use function parse_url;

error_reporting(E_DEPRECATED | E_WARNING | E_ERROR | E_PARSE);

/* include */

include_once('includes' . DIRECTORY_SEPARATOR . 'Autoloader.php');
include_once('TestCaseAbstract.php');

/* autoload */

$autoloader = new Autoloader();
$autoloader->init();

/* get the instance */

$config = Config::getInstance();

/* config */

$smtpUrl = getenv('SMTP_URL');
$dbUrl = getenv('DB_URL');
if ($smtpUrl)
{
	ini_set('SMTP', parse_url($smtpUrl, PHP_URL_HOST));
	ini_set('smtp_port', parse_url($smtpUrl, PHP_URL_PORT));
}
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
