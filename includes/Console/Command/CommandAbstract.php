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
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = [];

	/**
	 * prompt
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param array $promptArray
	 *
	 * @return string|null
	 */

	public function prompt(string $key = null, array $promptArray = []) : ?string
	{
		return array_key_exists($key, $promptArray) || array_key_exists('no-interaction', $promptArray) ? $promptArray[$key] : readline($key . ':');
	}

	/**
	 * get the help
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function getHelp() : ?string
	{
		$output = null;

		/* process command */

		foreach ($this->_commandArray as $commandKey => $commandValue)
		{
			$output .= str_pad($commandKey, 30) . $commandValue['description'] . PHP_EOL;

			/* process argument */

			if (is_array($commandValue['argumentArray']))
			{
				foreach ($commandValue['argumentArray'] as $argumentKey => $argumentValue)
				{
					$output .= str_repeat(' ', 2) . str_pad($argumentKey, 30) . $argumentValue['description'] . PHP_EOL;

					/* process option */

					if (is_array($argumentValue['optionArray']))
					{
						foreach ($argumentValue['optionArray'] as $optionKey => $optionValue)
						{
							$output .= str_repeat(' ', 4) . '--' . str_pad($optionKey, 28) . $optionValue['description'] . PHP_EOL;
						}
					}
				}
			}
		}
		return $output;
	}

	/**
	 * show the success
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function success() : string
	{
		return $this->_language->get('operation_completed') . PHP_EOL;
	}

	/**
	 * show the error
	 *
	 * @since 4.0.0
	 *
	 * @param bool $haltOnError halt on error flag
	 *
	 * @return string|null
	 */

	public function error(bool $haltOnError = false) : ?string
	{
		if ($haltOnError)
		{
			exit(1);
		}
		return $this->_language->get('error_occurred') . PHP_EOL;
	}
}
