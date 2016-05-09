<?php
namespace Redaxscript\Console;

use Redaxscript\Language;
use Redaxscript\Request;

/**
 * parent class to handle the command line interface
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Command
{
	public function init()
	{
		/* language */

		$language = Language::getInstance();
		$language::init();

		/* parser */

		$parser = new Parser(Request::getInstance());
		$parser->init();

		/* console */

		echo PHP_EOL . $language->get('name', '_package') . ' ' . $language->get('version', '_package') . PHP_EOL . PHP_EOL;

		/* verbose */

		if ($parser->getOption('verbose'))
		{
			print_r($parser->getArgument());
			print_r($parser->getOption());
		}
	}
}