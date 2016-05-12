<?php
namespace Redaxscript\Console;

/**
 * abstract class to create a command class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

abstract class CommandAbstract
{
	/**
	 * instance of the parser class
	 *
	 * @var object
	 */

	protected $_parser;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Parser $parser instance of the parser class
	 */

	public function __construct(Parser $parser)
	{
		$this->_parser = $parser;
	}

	/**
	 * get the help
	 *
	 * @since 3.0.0
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
			$output .= '  Usage: php console.php ' . $this->_commandArray['command'] . PHP_EOL;
		}
		return $output;
	}
}
