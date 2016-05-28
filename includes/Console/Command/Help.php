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
	 * @param string $mode name of the mode
	 *
	 * @return string
	 */

	public function run($mode = null)
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		return $this->_list($parser->getArgument(1));
	}

	/**
	 * list the help
	 *
	 * @since 3.0.0
	 *
	 * @param string $argumentKey
	 *
	 * @return string
	 */

	protected function _list($argumentKey = null)
	{
		$output = null;

		/* collect each help */

		if (!array_key_exists($argumentKey, $this->_namespaceArray))
		{
			$namespaceKeys = array_keys($this->_namespaceArray);
			$lastKey = end($namespaceKeys);
			foreach ($this->_namespaceArray as $commandKey => $commandClass)
			{
				$command = new $commandClass($this->_config, $this->_request);
				$output .= $command->getHelp();
				if ($commandKey !== $lastKey)
				{
					$output .= PHP_EOL;
				}
			}
		}

		/* else single help */

		else
		{
			$commandClass = $this->_namespaceArray[$argumentKey];
			$command = new $commandClass($this->_config, $this->_request);
			$output .= $command->getHelp();
		}
		return $output;
	}
}
