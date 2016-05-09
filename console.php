<?php
namespace Redaxscript;

use Redaxscript\Console;

error_reporting(E_ERROR || E_PARSE);

/* bootstrap */

include_once('includes/bootstrap.php');

/* language */

$language = Language::getInstance();
$language::init();

/* parser */

$parser = new Console\Parser(Request::getInstance());
$parser->init();

/* console */

echo PHP_EOL . $language->get('name', '_package') . ' ' . $language->get('version', '_package') . PHP_EOL . PHP_EOL;

/* verbose */

if ($parser->getOption('verbose'))
{
	print_r($parser->getArgument());
	print_r($parser->getOption());
}
