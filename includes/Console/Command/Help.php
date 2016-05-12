<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\CommandAbstract;

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
		'status' => 'Redaxscript\Console\Command\Status',
		'setting' => 'Redaxscript\Console\Command\Setting',
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
		$output = null;
		$commandKey = $this->_parser->getArgument(2);

		/* collect each help */

		if (!array_key_exists($commandKey, $this->_namespaceArray))
		{
			$output = PHP_EOL;
			foreach ($this->_namespaceArray as $commandClass)
			{
				$command = new $commandClass($this->_parser);
				$output .= $command->getHelp() . PHP_EOL;
			}
		}

		/* else specified help */

		else
		{
			$output .= PHP_EOL;
			$commandClass = $this->_namespaceArray[$commandKey];
			$command = new $commandClass($this->_parser);
			$output .= $command->getHelp() . PHP_EOL;
		}
		return $output;
	}
}
