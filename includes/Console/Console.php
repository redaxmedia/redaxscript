<?php
namespace Redaxscript\Console;

/**
 * parent class to handle the command line interface
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Console extends ConsoleAbstract
{
	/**
	 * init the class
	 *
	 * @since 3.0.0
	 * 
	 * @return string
	 */

	public function init()
	{
		$parser = new Parser($this->_request);
		$parser->init();

		/* run command */

		$commandKey = $parser->getArgument(1);
		if (!array_key_exists($commandKey, $this->_namespaceArray))
		{
			$commandKey = 'help';
		}
		$commandClass = $this->_namespaceArray[$commandKey];
		$command = new $commandClass($this->_config, $this->_request);
		return $command->run();
	}
}
