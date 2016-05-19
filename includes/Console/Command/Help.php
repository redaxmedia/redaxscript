<?php
namespace Redaxscript\Console\Command;

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
		'help' => array(
			'description' => 'Help command',
			'argumentArray' => array(
				'<command>' => array(
					'description' => 'Show help for the <command>'
				)
			)
		)
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

		return $this->_list($parser->getArgument());
	}

	/**
	 * list the help
	 *
	 * @since 3.0.0
	 *
	 * @param array $argumentArray
	 *
	 * @return string
	 */

	protected function _list($argumentArray = array())
	{
		$output = PHP_EOL;
		$commandKey = $argumentArray[2];

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
