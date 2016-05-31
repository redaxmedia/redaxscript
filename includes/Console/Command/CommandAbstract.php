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
	 * prompt
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param array $promptArray
	 *
	 * @return string
	 */

	public function prompt($key = null, $promptArray = array())
	{
		return $promptArray[$key] || $promptArray['no-interaction'] || !function_exists('readline') ? $promptArray[$key] : readline($key . ':');
	}

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
		foreach ($this->_commandArray as $commandKey => $commandValue)
		{
			$output .= str_pad($commandKey, 30) . $commandValue['description'] . PHP_EOL;
			foreach ($commandValue['argumentArray'] as $argumentKey => $argumentValue)
			{
				$output .= '  ' . str_pad($argumentKey, 30) . $argumentValue['description'] . PHP_EOL;
				foreach ($argumentValue['optionArray'] as $optionKey => $optionValue)
				{
					$output .= '    --' . str_pad($optionKey, 28) . $optionValue['description'] . PHP_EOL;
				}
			}
		}
		return $output;
	}
}
