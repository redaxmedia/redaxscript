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
$request = Request::getInstance();
$config = Config::getInstance();
$argv = $request->getServer('argv');

/* mysql and pgsql */

if (in_array('mysql', $argv) || in_array('pgsql', $argv))
{
	if (in_array('mysql', $request->getServer('argv')))
	{
		echo 'MySQL - ';
		$config->set('dbType', 'mysql');
		$config->set('dbUser', 'root');
	}
	if (in_array('pgsql', $argv))
	{
		echo 'PostgreSQL - ';
		$config->set('dbType', 'pgsql');
		$config->set('dbUser', 'postgres');
	}
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
