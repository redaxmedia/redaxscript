<?php
namespace Redaxscript;

/* strict reporting */

error_reporting(E_STRICT || E_ERROR);

/* include files */

include_once('includes/Autoloader.php');
include_once('BenchCase.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* sqlite */

$config->set('dbType', 'sqlite');

/* database */

Db::construct($config);
Db::init();

/* installer */

$installer = new Installer($config);
$installer->init();
$installer->rawDrop();
$installer->rawCreate();
$installer->insertData();

/* hook */

Hook::construct($registry);
Hook::init();

/* language */

$language = Language::getInstance();
$language::init('en');
