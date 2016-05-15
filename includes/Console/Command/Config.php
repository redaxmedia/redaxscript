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
		'config' => array(
			'description' => 'Config command',
			'argumentArray' => array(
				'list' => array(
					'description' => 'List the configuration'
				),
				'set' => array(
					'description' => 'Set the configuration',
					'optionArray' => array(
						'db-type' => array(
							'description' => 'Required database type',
							'required' => 'required'
						),
						'db-host' => array(
							'description' => 'Required database host or file',
							'required' => 'required'
						),
						'db-name' => array(
							'description' => 'Optional database name'
						),
						'db-user' => array(
							'description' => 'Optional database user'
						),
						'db-password' => array(
							'description' => 'Optional database password'
						),
						'db-prefix' => array(
							'description' => 'Optional database prefix'
						)
					)
				),
				'parse' => array(
					'description' => 'Parse the configuration',
					'optionArray' => array(
						'db-url' => array(
							'description' => 'Required database url from ENV variable',
							'required' => 'required'
						)
					)
				),
				'write' => array(
					'description' => 'Write to the configuration file'
				)
			)
		)
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

		$argumentKey = $parser->getArgument(2);
		if ($argumentKey === 'list')
		{
			return $this->_list();
		}
		if ($argumentKey === 'write')
		{
			return $this->_write();
		}
		return $this->getHelp();
	}

	/**
	 * list the config
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function _list()
	{
		$output = null;
		$configArray = $this->_config->get();

		/* process config */

		foreach ($configArray as $key => $value)
		{
			if ($key === 'dbPassword' || $key === 'dbSalt')
			{
				$value = str_repeat('*', strlen($value));
			}
			if ($value)
			{
				$output .= str_pad($key, 20) . $value . PHP_EOL;
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
		return $this->_config->write();
	}
}
