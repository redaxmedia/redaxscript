<?php
namespace Redaxscript;

use function error_reporting;

error_reporting(E_DEPRECATED | E_WARNING | E_ERROR | E_PARSE);

/* include */

include_once('includes' . DIRECTORY_SEPARATOR . 'Autoloader.php');
include_once('TestCaseAbstract.php');

/* autoload */

$autoloader = new Autoloader();
$autoloader->init();

/* get the instance */

$config = Config::getInstance();

/* database */

Db::construct($config);
Db::init();

/* language */

$language = Language::getInstance();
$language->init();
