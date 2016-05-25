<?php
namespace Redaxscript;

/* autoload */

include_once('includes/Autoloader.php');
include_once('tests/phpunit/TestCaseAbstract.php');

/* deprecated */

include_once('includes/query.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* set config */

$dbUrl = getenv('DB_URL');
if ($dbUrl)
{
	$config->parse($dbUrl);
}
else
{
	$config->set('dbType', 'sqlite');
}

/* database */

Db::construct($config);
Db::init();

/* installer */

$installer = new Installer($config);
$installer->init();
$installer->rawDrop();
$installer->rawCreate();
$installer->insertData(array(
	'adminName' => 'Test',
	'adminUser' => 'test',
	'adminPassword' => 'test',
	'adminEmail' => 'test@test.com'
));
Db::forTablePrefix('users')->whereIdIs(1)->findOne()->set('password', 'test')->save();

/* test module */

if (is_dir('modules/TestDummy'))
{
	$testDummy = new Modules\TestDummy\TestDummy;
	$testDummy->install();
}

/* hook */

Hook::construct($registry);
Hook::init();

/* language */

$language = Language::getInstance();
$language::init();
