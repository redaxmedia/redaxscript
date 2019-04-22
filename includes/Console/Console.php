<?php
namespace Redaxscript\Console;

use function array_key_exists;
use function is_array;
use function is_string;
use function method_exists;

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
	 * @return string|null
	 */

	public function init(string $mode = null) : ?string
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$commandKey = $parser->getArgument(0);
		if (is_string($commandKey) && is_array($this->_namespaceArray) && array_key_exists($commandKey, $this->_namespaceArray))
		{
			$commandClass = $this->_namespaceArray[$commandKey];
			if (method_exists($commandClass, 'run'))
			{
				$command = new $commandClass($this->_registry, $this->_request, $this->_language, $this->_config);
				return $command->run($mode);
			}
		}
		return null;
	}
}
