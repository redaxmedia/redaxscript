<?php

/* include files */

include_once('includes/autoloader.php');
include_once('includes/migrate.php');

/* init autoloader */

Redaxscript_Autoloader::init();

/* migrate registry */

$registry = Redaxscript_Registry::instance();
$registry->init(migrate_constants());