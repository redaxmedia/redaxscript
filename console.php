<?php
namespace Redaxscript;

use Redaxscript\Console;

error_reporting(E_ERROR || E_PARSE);

/* bootstrap */

include_once('includes/bootstrap.php');

/* console */

$console = new Console\Console(Config::getInstance(), Request::getInstance());
echo $console->init();
