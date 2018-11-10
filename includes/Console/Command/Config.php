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
					'description' => 'Lock the production environment'
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
			return $this->_set($parser->getOption()) ? $this->success() : $this->error($haltOnError);
		}
		if ($argumentKey === 'parse')
		{
			return $this->_parse($parser->getOption()) ? $this->success() : $this->error($haltOnError);
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
		$configArray = $this->_config->get();

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
	 * @since 3.0.0
	 *
	 * @return bool
	 */

	protected function _lock() : bool
	{
		$this->_config->set('env', 'production');
		return $this->_config->write();
	}
}
