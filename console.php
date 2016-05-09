<?php
namespace Redaxscript;

error_reporting(0);

/* include files */

include_once('includes/Autoloader.php');

/* init */

Autoloader::init();

echo Language::get('name', '_package');