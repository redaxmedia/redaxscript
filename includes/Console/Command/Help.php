<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\CommandAbstract;
use Redaxscript\Console\Parser;

/**
 * children class to execute the help command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Help extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = array(
		'name' => 'Help',
		'command' => 'help',
		'author' => 'Redaxmedia',
		'description' => 'Help for the console',
		'version' => '3.0.0'
	);

	/**
	 * array of namespaces
	 *
	 * @var string
	 */

	protected $_namespaceArray = array(
		'backup' => 'Redaxscript\Console\Command\Backup',
		'config' => 'Redaxscript\Console\Command\Config',
		'help' => 'Redaxscript\Console\Command\Help',
		'install' => 'Redaxscript\Console\Command\Install',
		'reinstall' => 'Redaxscript\Console\Command\Reinstall',		
		'setting' => 'Redaxscript\Console\Command\Setting',
		'status' => 'Redaxscript\Console\Command\Status',
		'uninstall' => 'Redaxscript\Console\Command\Uninstall'
	);

	/**
	 * run the command
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function run()
	{
		$parser = new Parser($this->_request);
		$parser->init();

		/* run command */

		$commandKey = $parser->getArgument(2);
		return $this->_help($commandKey);
	}

	/**
	 * show the help
	 *
	 * @since 3.0.0
	 *
	 * @param string $commandKey
	 * @return string
	 */

	protected function _help($commandKey = null)
	{
		$output = PHP_EOL;

		/* collect multiple help */

		if (!array_key_exists($commandKey, $this->_namespaceArray))
		{
			foreach ($this->_namespaceArray as $commandClass)
			{
				$command = new $commandClass($this->_config, $this->_request);
				$output .= $command->getHelp() . PHP_EOL;
			}
		}

		/* else specified help */

		else
		{
			$commandClass = $this->_namespaceArray[$commandKey];
			$command = new $commandClass($this->_config, $this->_request);
			$output .= $command->getHelp() . PHP_EOL;
		}
		return $output;
	}
}
