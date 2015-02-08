<?php
namespace Redaxscript;

/* include as needed */

include_once('includes/Autoloader.php');
include_once('TestCase.php');
include_once('stubs/hook_function.php');
include_once('stubs/hook_method.php');

/* init */

Autoloader::init();
Request::init();

/* set config */

Config::set('type', 'mysql');
Config::set('host', 'redaxscript.com');
Config::set('name', 'd01ae38a');
Config::set('user', 'd01ae38a');
Config::set('password', 'travis');

/* get instance */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* database and hook */

Db::init($config);
Hook::init($registry);

/* language */

$language = Language::getInstance();
$language::init('en');