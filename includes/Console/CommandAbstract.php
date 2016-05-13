<?php
namespace Redaxscript\Console;

use Redaxscript\Config as BaseConfig;
use Redaxscript\Request as BaseRequest;

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
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param BaseConfig $config instance of the config class
	 * @param BaseRequest $request instance of the request class
	 */

	public function __construct(BaseConfig $config, BaseRequest $request)
	{
		$this->_config = $config;
		$this->_request = $request;
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
			$output .= $this->_commandArray['command'] . PHP_EOL;
		}
		return $output;
	}
}
