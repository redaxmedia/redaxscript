<?php

/* include as needed */

include_once('includes/Autoloader.php');

/* init */

Redaxscript_Autoloader::init();
Redaxscript_Request::init();

/* set config */

Redaxscript_Config::set('type', 'mysql');
Redaxscript_Config::set('host', 'redaxscript.com');
Redaxscript_Config::set('name', 'd01ae38a');
Redaxscript_Config::set('user', 'd01ae38a');
Redaxscript_Config::set('password', 'travis');

/* registry and config */

$registry = Redaxscript_Registry::getInstance();
$config = Redaxscript_Config::getInstance();

/* database */

Redaxscript_Db::init($config);

/* detection */

$detectionLanguage = New Redaxscript_Detection_Language($registry);
$detectionTemplate = New Redaxscript_Detection_Template($registry);

/* set language and template */

$registry->set('language', $detectionLanguage->getOutput());
$registry->set('template', $detectionTemplate->getOutput());

/* language */

$language = Redaxscript_Language::getInstance();
$language->init($registry->get('language'));