<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use function array_key_exists;
use function array_keys;
use function end;
use function is_array;
use function is_string;
use function method_exists;

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

	protected $_commandArray =
	[
		'help' =>
		[
			'description' => 'Help command',
			'argumentArray' =>
			[
				'<command>' =>
				[
					'description' => 'Show help for the <command>'
				]
			]
		]
	];

	/**
	 * run the command
	 *
	 * @since 3.0.0
	 *
	 * @param string $mode name of the mode
	 *
	 * @return string|null
	 */

	public function run(string $mode = null) : ?string
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
	 * @return string|null
	 */

	protected function _list(string $argumentKey = null) : ?string
	{
		$output = null;

		/* collect each help */

		if (!is_array($this->_namespaceArray) || !array_key_exists($argumentKey, $this->_namespaceArray))
		{
			$namespaceKeys = array_keys($this->_namespaceArray);
			$lastKey = end($namespaceKeys);
			foreach ($this->_namespaceArray as $commandKey => $commandClass)
			{
				if (method_exists($commandClass, 'getHelp'))
				{
					$command = new $commandClass($this->_registry, $this->_request, $this->_language, $this->_config);
					$output .= $command->getHelp();
					if ($commandKey !== $lastKey)
					{
						$output .= PHP_EOL;
					}
				}
			}
		}

		/* else single help */

		else
		{
			$commandClass = $this->_namespaceArray[$argumentKey];
			if (method_exists($commandClass, 'getHelp'))
			{
				$command = new $commandClass($this->_registry, $this->_request, $this->_language, $this->_config);
				$output .= $command->getHelp();
			}
		}
		return $output;
	}
}
