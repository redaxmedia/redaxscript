<?php

/* include files */

include_once('includes/Autoloader.php');
include_once('includes/Singleton.php');
include_once('includes/migrate.php');
include_once('config.php');

/* init */

Redaxscript_Autoloader::init();
Redaxscript_Request::init();

/* config and registry */

$config = Redaxscript_Config::getInstance();
$registry = Redaxscript_Registry::getInstance();

/* migrate constants */

$registry->init(migrate_constants());

/* connect database */

Redaxscript_Db::connect($registry, $config);

/* detection */

$detectionLanguage = New Redaxscript_Detection_Language($registry);
$detectionTemplate = New Redaxscript_Detection_Template($registry);

/* set language and template */

$registry->set('language', $detectionLanguage->getOutput());
$registry->set('template', $detectionTemplate->getOutput());

/* language */

$language = Redaxscript_Language::getInstance();
$language->init($registry->get('language'));

/* deprecated */

define('LANGUAGE', $detectionLanguage->getOutput());
define('TEMPLATE', $detectionTemplate->getOutput());