<?php
namespace Redaxscript;

/* autoload */

include_once('includes/Autoloader.php');
include_once('TestCaseAbstract.php');

/* deprecated */

include_once('includes/query.php');

/* init */

$autoloader = new Autoloader();
$autoloader->init();

/* get instance */

$registry = Registry::getInstance();
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

/* database */

Db::construct($config);
Db::init();

/* installer */

$installer = new Installer($config);
$installer->init();
$installer->rawDrop();
$installer->rawCreate();
$installer->insertData(
[
	'adminName' => 'Test',
	'adminUser' => 'test',
	'adminPassword' => 'test',
	'adminEmail' => 'test@test.com'
]);

/* test user */

Db::forTablePrefix('users')
	->whereIdIs(1)
	->findOne()
	->set(
	[
		'password' => 'test',
		'description' => 'test',
		'language' => 'en'
	])
	->save();

/* test module */

if (is_dir('modules/TestDummy'))
{
	$testDummy = new Modules\TestDummy\TestDummy;
	$testDummy->install();
}

/* language */

$language = Language::getInstance();
$language->init();

/* hook */

Hook::construct($registry);
Hook::init();

