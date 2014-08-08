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

/* database and hook */

Redaxscript\Db::init($config);
Redaxscript_Hook::init($registry);

/* language */

$language = Redaxscript_Language::getInstance();
$language::init('en');