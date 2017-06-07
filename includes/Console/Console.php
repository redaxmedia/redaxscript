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
	 * @param string $mode name of the mode
	 *
	 * @return string|boolean
	 */

	public function init($mode = null)
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$commandKey = $parser->getArgument(0);
		if (is_string($commandKey) && is_array($this->_namespaceArray) && array_key_exists($commandKey, $this->_namespaceArray))
		{
			$commandClass = $this->_namespaceArray[$commandKey];
			if (class_exists($commandClass))
			{
				$command = new $commandClass($this->_registry, $this->_request, $this->_language, $this->_config);
				return $command->run($mode);
			}
		}
		return false;
	}
}
