<?php

/* include files */

include_once('includes/Autoloader.php');
include_once('includes/migrate.php');

/* init */

Redaxscript_Autoloader::init();

/* migrate registry */

$registry = Redaxscript_Registry::getInstance();
$registry->init(migrate_constants());

/* language */

$language = Redaxscript_Language::getInstance();
$language->init($registry->get('language'));