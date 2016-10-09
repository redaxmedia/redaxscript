<?php
namespace Redaxscript;

/* include files */

include_once('includes/Autoloader.php');
include_once('BenchCaseAbstract.php');

/* init */

$autoloader = new Autoloader();
$autoloader->init();

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$config = Config::getInstance();

/* request and config */

$request->init();
$config->init();

/* set config */

$config->set('dbType', 'sqlite');
$config->set('dbHost', ':memory:');

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

/* hook */

Hook::construct($registry);
Hook::init();

/* language */

$language = Language::getInstance();
$language->init();