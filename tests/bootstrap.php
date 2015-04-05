<?php
namespace Redaxscript;

/* include as needed */

include_once('includes/Autoloader.php');
include_once('TestCase.php');
include_once('stubs/hook_function.php');
include_once('stubs/hook_method.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$config = Config::getInstance();
$request = Request::getInstance();

/* mysql */

if (in_array('mysql', $request->get('argv')))
{
	echo 'MySQL - ';
	$config->set('type', 'mysql');
	$config->set('host', 'localhost');
	$config->set('name', 'test');
	$config->set('user', 'root');
}

/* sqlite */

else
{
	echo 'SQLite - ';
}

/* database */

Db::init($config);

/* installer */

$installer = new Installer();
$installer->init($config);
$installer->rawDrop();
$installer->rawCreate();
$installer->insertData();

/* hook */

Hook::init($registry);

/* language */

$language = Language::getInstance();
$language::init('en');