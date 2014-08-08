<?php

/* include as needed */

include_once('includes/Autoloader.php');

/* init */

Redaxscript\Autoloader::init();
Redaxscript\Request::init();

/* registry and config */

$registry = Redaxscript\Registry::getInstance();
$config = Redaxscript\Config::getInstance();

/* database and hook */

Redaxscript\Db::init($config);
Redaxscript_Hook::init($registry);

/* detection */

$detectionLanguage = new Redaxscript_Detection_Language($registry);
$detectionTemplate = new Redaxscript_Detection_Template($registry);

/* set language and template */

$registry->set('language', $detectionLanguage->getOutput());
$registry->set('template', $detectionTemplate->getOutput());

/* language */

$language = Redaxscript\Language::getInstance();
$language->init($registry->get('language'));

/* define deprecated constants */

define('LANGUAGE', $detectionLanguage->getOutput());
define('TEMPLATE', $detectionTemplate->getOutput());