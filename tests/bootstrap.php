<?php
namespace Redaxscript;

/* strict reporting */

error_reporting(E_STRICT || E_ERROR);

/* include files */

include_once('includes/Autoloader.php');
include_once('TestCase.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* get environment */

$dbType = getenv('DB_TYPE');

/* mysql and pgsql */

if ($dbType === 'mysql' || $dbType === 'pgsql')
{
	if ($dbType === 'mysql')
	{
		echo 'MySQL - ';
		$config->set('dbUser', 'root');
	}
	else
	{
		echo 'PostgreSQL - ';
		$config->set('dbUser', 'postgres');
	}
	$config->set('dbType', $dbType);
	$config->set('dbHost', '127.0.0.1');
	$config->set('dbName', 'test');
	$config->set('dbPassword', 'test');
	$config->set('dbSalt', 'test');
}

/* sqlite */

else
{
	echo 'SQLite - ';
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
$installer->insertData();

/* test dummy */

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
$language::init('en');
