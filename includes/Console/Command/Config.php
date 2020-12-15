<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use function str_pad;
use function str_repeat;
use function strlen;

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

	protected $_commandArray =
	[
		'config' =>
		[
			'description' => 'Config command',
			'argumentArray' =>
			[
				'list' =>
				[
					'description' => 'List the configuration'
				],
				'set' =>
				[
					'description' => 'Set the configuration',
					'optionArray' =>
					[
						'db-type' =>
						[
							'description' => 'Required database type'
						],
						'db-host' =>
						[
							'description' => 'Required database host or file'
						],
						'db-name' =>
						[
							'description' => 'Optional database name'
						],
						'db-user' =>
						[
							'description' => 'Optional database user'
						],
						'db-password' =>
						[
							'description' => 'Optional database password'
						],
						'db-prefix' =>
						[
							'description' => 'Optional database prefix'
						]
					]
				],
				'reset' =>
				[
					'description' => 'Reset the configuration'
				],
				'parse' =>
				[
					'description' => 'Parse the configuration',
					'optionArray' =>
					[
						'db-url' =>
						[
							'description' => 'Required database url'
						]
					]
				],
				'lock' =>
				[
					'description' => 'Lock the configuration'
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

		$argumentKey = $parser->getArgument(1);
		$haltOnError = (bool)$parser->getOption('halt-on-error');
		if ($argumentKey === 'list')
		{
			return $this->_list();
		}
		if ($argumentKey === 'set')
		{
			return $this->_set($parser->getOptionArray()) ? $this->success() : $this->error($haltOnError);
		}
		if ($argumentKey === 'reset')
		{
			return $this->_reset() ? $this->success() : $this->error($haltOnError);
		}
		if ($argumentKey === 'parse')
		{
			return $this->_parse($parser->getOptionArray()) ? $this->success() : $this->error($haltOnError);
		}
		if ($argumentKey === 'lock')
		{
			return $this->_lock() ? $this->success() : $this->error($haltOnError);
		}
		return $this->getHelp();
	}

	/**
	 * list the config
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	protected function _list() : ?string
	{
		$output = null;
		$configArray = $this->_config->getArray();

		/* process config */

		foreach ($configArray as $key => $value)
		{
			if ($key === 'dbPassword')
			{
				$value = str_repeat('*', strlen($value));
			}
			if ($value)
			{
				$output .= str_pad($key, 30) . $value . PHP_EOL;
			}
		}
		return $output;
	}

	/**
	 * set the config
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return bool
	 */

	protected function _set(array $optionArray = []) : bool
	{
		$dbType = $this->prompt('db-type', $optionArray);
		$dbHost = $this->prompt('db-host', $optionArray);
		if ($dbType && $dbHost)
		{
			$this->_config->set('dbType', $dbType);
			$this->_config->set('dbHost', $dbHost);
			$this->_config->set('dbName', $optionArray['db-name']);
			$this->_config->set('dbUser', $optionArray['db-user']);
			$this->_config->set('dbPassword', $optionArray['db-password']);
			$this->_config->set('dbPrefix', $optionArray['db-prefix']);
			return $this->_config->write();
		}
		return false;
	}

	/**
	 * reset the config
	 *
	 * @since 4.5.0
	 *
	 * @return bool
	 */

	protected function _reset() : bool
	{
		$this->_config->reset();
		return $this->_config->write();
	}

	/**
	 * parse the config
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return bool
	 */

	protected function _parse(array $optionArray = []) : bool
	{
		$dbUrl = $this->prompt('db-url', $optionArray);
		if ($dbUrl)
		{
			$this->_config->parse($dbUrl);
			return $this->_config->write();
		}
		return false;
	}

	/**
	 * lock the config
	 *
	 * @since 4.4.0
	 *
	 * @return bool
	 */

	protected function _lock() : bool
	{
		$this->_config->set('lock', '1');
		return $this->_config->write();
	}
}
