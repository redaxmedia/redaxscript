<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;

/**
 * children class to execute the config command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Config extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = array(
		'name' => 'Config',
		'command' => 'config',
		'author' => 'Redaxmedia',
		'description' => 'Handle the configuration file',
		'version' => '3.0.0'
	);

	/**
	 * run the command
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function run()
	{
		$parser = new Parser($this->_request);
		$parser->init();

		/* run command */

		$commandKey = $parser->getArgument(2);
		if ($commandKey === 'show')
		{
			return $this->_show();
		}
		if ($commandKey === 'write')
		{
			return $this->_write();
		}
	}

	/**
	 * show the config
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function _show()
	{
		$output = null;

		/* process config */

		foreach ($this->_config->get() as $key => $value)
		{
			if ($key === 'dbPassword' || $key === 'dbSalt')
			{
				$value = str_repeat('*', strlen($value));
			}
			if ($value)
			{
				$output .= $key . ': ' . $value . PHP_EOL;
			}
		}
		return $output;
	}

	/**
	 * write the config
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function _write()
	{
		return PHP_EOL;
	}
}
