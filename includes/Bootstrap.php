<?php

/* include files */

include_once('includes/Autoloader.php');
include_once('includes/migrate.php');

/* init autoloader */

Redaxscript_Autoloader::init();

/* migrate registry */

$registry = Redaxscript_Registry::instance();
$registry->init(migrate_constants());

/* init language */

$language = Redaxscript_Language::instance();
$language->init($registry->get('language'));