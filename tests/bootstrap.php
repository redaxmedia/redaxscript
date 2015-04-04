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