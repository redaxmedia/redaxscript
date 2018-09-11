<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Db;

/**
 * children class to execute the status command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Status extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'status' =>
		[
			'description' => 'Status command',
			'argumentArray' =>
			[
				'database' =>
				[
					'description' => 'Show database status',
					'wordingArray' =>
					[
						'No connection found',
						'Database tables missing',
						'Connection established'
					]
				],
				'system' =>
				[
					'description' => 'Show system requirements',
					'wordingArray' =>
					[
						'Failed',
						'Passed'
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
	 * @return string|null
	 */

	public function run(string $mode = null) : ?string
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$argumentKey = $parser->getArgument(1);
		if ($argumentKey === 'database')
		{
			return $this->_database();
		}
		if ($argumentKey === 'system')
		{
			return $this->_system();
		}
		return $this->getHelp();
	}

	/**
	 * database status
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	protected function _database() : ?string
	{
		$output = null;
		$status = Db::getStatus();
		$wordingArray = $this->_commandArray['status']['argumentArray']['database']['wordingArray'];
		if (is_array($wordingArray) && array_key_exists($status, $wordingArray))
		{
			$output = $wordingArray[$status] . PHP_EOL;
		}
		return $output;
	}

	/**
	 * system status
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	protected function _system() : ?string
	{
		$output = null;
		$statusArray = $this->_getStatusArray();
		$wordingArray = $this->_commandArray['status']['argumentArray']['system']['wordingArray'];

		/* process status */

		foreach ($statusArray as $key => $valueArray)
		{
			if (is_array($wordingArray) && array_key_exists($valueArray['status'], $wordingArray))
			{
				$output .= str_pad($key, 30) . str_pad($valueArray['value'], 60) . $wordingArray[$valueArray['status']] . PHP_EOL;
			}
		}
		return $output;
	}

	/**
	 * get the status array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _getStatusArray() : array
	{
		$driverArray = $this->_registry->get('driverArray');
		$moduleArray = $this->_registry->get('moduleArray');
		$testOsArray =
		[
			'linux',
			'windows'
		];
		$testDriverArray =
		[
			'mssql',
			'mysql',
			'pgsql',
			'sqlite'
		];
		$testModuleArray =
		[
			'mod_deflate',
			'mod_headers',
			'mod_rewrite'
		];
		$statusArray =
		[
			'OS' =>
			[
				'value' => $this->_registry->get('phpOs'),
				'status' => in_array($this->_registry->get('phpOs'), $testOsArray) ? 1 : 0
			],
			'PHP' =>
			[
				'value' => $this->_registry->get('phpVersion'),
				'status' => $this->_registry->get('phpStatus') ? 1 : 0
			],
			'SESSION' =>
			[
				'value' => $this->_registry->get('sessionStatus'),
				'status' => $this->_registry->get('sessionStatus') ? 1 : 0
			]
		];

		/* process driver */

		if (is_array($driverArray))
		{
			foreach ($testDriverArray as $value)
			{
				$statusArray[$value] =
				[
					'status' => in_array($value, $driverArray) ? 1 : 0
				];
			}
		}

		/* process module */

		if (is_array($moduleArray))
		{
			foreach ($testModuleArray as $value)
			{
				$statusArray[$value] =
				[
					'status' => in_array($value, $moduleArray) ? 1 : 0
				];
			}
		}
		return $statusArray;
	}
}
