<?php
namespace Redaxscript;

/* include as needed */

include_once('includes/Autoloader.php');

/* init */

Autoloader::init();
Request::init();

/* deprecated startup */

startup();

/* registry and config */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* migrate constants */

$registry->init(migrate_constants());

/* database and hook */

Db::init($config);
Hook::init($registry);

/* detector */

$detectorLanguage = new Detector\Language($registry);
$detectorTemplate = new Detector\Template($registry);

/* set language and template */

$registry->set('language', $detectorLanguage->getOutput());
$registry->set('template', $detectorTemplate->getOutput());

/* language */

$language = Language::getInstance();
$language->init($registry->get('language'));

/* define deprecated constants */

define('LANGUAGE', $detectorLanguage->getOutput());
define('TEMPLATE', $detectorTemplate->getOutput());