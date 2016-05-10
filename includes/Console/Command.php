<?php
namespace Redaxscript\Console;

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
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * array of commands
	 *
	 * @var string
	 */

	protected static $_commandArray = array(
		'backup' => 'Redaxscript\Console\Command\Backup',
		'config' => 'Redaxscript\Console\Command\Config',
		'help' => 'Redaxscript\Console\Command\Help',
		'install' => 'Redaxscript\Console\Command\Install',
		'list' => 'Redaxscript\Console\Command\List',
		'status' => 'Redaxscript\Console\Command\Status',
		'setting' => 'Redaxscript\Console\Command\Setting'
	);

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Request $request instance of the registry class
	 */

	public function __construct(Request $request)
	{
		$this->_request = $request;
	}
	
	/**
	 * init the class
	 *
	 * @since 3.0.0
	 */
	
	public function init()
	{
		/* parser */

		$parser = new Parser(Request::getInstance());
		$parser->init();

		/* verbose */

		if ($parser->getOption('verbose'))
		{
			print_r($parser->getArgument());
			print_r($parser->getOption());
		}
	}
}