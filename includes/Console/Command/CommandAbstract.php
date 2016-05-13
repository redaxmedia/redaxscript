<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\ConsoleAbstract;

/**
 * abstract class to create a command class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

abstract class CommandAbstract extends ConsoleAbstract
{
	/**
	 * get the help
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function getHelp()
	{
		$output = null;
		if (array_key_exists('description', $this->_commandArray))
		{
			$output .= $this->_commandArray['description'] . PHP_EOL;
		}
		if (array_key_exists('command', $this->_commandArray))
		{
			$output .= $this->_commandArray['command'] . PHP_EOL;
		}
		return $output;
	}
}
