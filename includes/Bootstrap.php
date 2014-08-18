<?php
namespace Redaxscript;

/* include as needed */

include_once('includes/Autoloader.php');

/* init */

Autoloader::init();
Request::init();

/* registry and config */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* database and hook */

Db::init($config);
Hook::init($registry);

/* detection */

$detectionLanguage = new Detection\Language($registry);
$detectionTemplate = new Detection\Template($registry);

/* set language and template */

$registry->set('language', $detectionLanguage->getOutput());
$registry->set('template', $detectionTemplate->getOutput());

/* language */

$language = Language::getInstance();
$language->init($registry->get('language'));

/* define deprecated constants */

define('LANGUAGE', $detectionLanguage->getOutput());
define('TEMPLATE', $detectionTemplate->getOutput());