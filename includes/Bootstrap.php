<?php

/* include files */

include_once('includes/Autoloader.php');
include_once('includes/migrate.php');

/* init */

Redaxscript_Autoloader::init();
Redaxscript_Request::init();

/* migrate registry */

$registry = Redaxscript_Registry::instance();
$registry->init(migrate_constants());

/* language */

$language = Redaxscript_Language::instance();
$language->init($registry->get('language'));