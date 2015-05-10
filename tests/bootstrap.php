<?php
namespace Redaxscript;

/* strict reporting */

error_reporting(E_STRICT || E_ERROR);

/* include as needed */

include_once('includes/Autoloader.php');
include_once('TestCase.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$request = Request::getInstance();
$config = Config::getInstance();

/* mysql and pgsql */

if (in_array('mysql', $request->getServer('argv')) || in_array('pgsql', $request->getServer('argv')))
{
	if (in_array('mysql', $request->getServer('argv')))
	{
		echo 'MySQL - ';
		$config->set('dbType', 'mysql');
		$config->set('dbUser', 'root');
	}
	if (in_array('pgsql', $request->getServer('argv')))
	{
		echo 'PostgreSQL - ';
		$config->set('dbType', 'pgsql');
		$config->set('dbUser', 'postgres');
	}
	$config->set('dbHost', 'localhost');
	$config->set('dbName', 'test');
	$config->set('dbPassword', 'test');
}

/* sqlite */

else
{
	echo 'SQLite - ';
	$config->set('dbType', 'sqlite');
}

/* database */

Db::init($config);

/* installer */

$installer = new Installer();
$installer->init($config);
$installer->rawDrop();
$installer->rawCreate();
$installer->insertData();
if (is_dir('modules/Test'))
{
	$test = new Modules\Test\Test;
	$test->install();
}

/* hook */

Hook::init($registry);

/* language */

$language = Language::getInstance();
$language::init('en');
