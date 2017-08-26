<?php
namespace Redaxscript;

error_reporting(E_DEPRECATED | E_WARNING | E_ERROR | E_PARSE);

/* include */

include_once('includes' . DIRECTORY_SEPARATOR . 'Autoloader.php');
include_once('TestCaseAbstract.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_admin.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_list.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_query.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'admin_router.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'comments.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'contents.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'navigation.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'query.php');
include_once('includes' . DIRECTORY_SEPARATOR . 'router.php');

/* autoload */

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
