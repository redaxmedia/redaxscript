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
						],
						'db-env' =>
						[
							'description' => 'Get variable from ENV'
						]
					]
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
	 * @return string
	 */

	public function run($mode = null)
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$argumentKey = $parser->getArgument(1);
		if ($argumentKey === 'list')
		{
			return $this->_list();
		}
		if ($argumentKey === 'set')
		{
			return $this->_set($parser->getOption());
		}
		if ($argumentKey === 'parse')
		{
			return $this->_parse($parser->getOption());
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

	protected function _list()
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
	 * @return boolean
	 */

	protected function _set($optionArray = [])
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
			$this->_config->set('dbSalt', sha1(uniqid()));
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
	 * @return boolean
	 */

	protected function _parse($optionArray = [])
	{
		$dbUrl = $this->prompt('db-url', $optionArray);
		$dbUrl = $optionArray['db-env'] ? getenv($dbUrl) : $dbUrl;
		if ($dbUrl)
		{
			$this->_config->parse($dbUrl);
			return $this->_config->write();
		}
		return false;
	}
}
