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
	 * @return string
	 */

	public function run($mode = null)
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
	 * @return string
	 */

	protected function _database()
	{
		$status = Db::getStatus();
		$wordingArray = $this->_commandArray['status']['argumentArray']['database']['wordingArray'];
		if (array_key_exists($status, $wordingArray))
		{
			return $wordingArray[$status] . PHP_EOL;
		}
	}

	/**
	 * system status
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	protected function _system()
	{
		$output = null;
		$statusArray = $this->_getStatusArray();
		$wordingArray = $this->_commandArray['status']['argumentArray']['system']['wordingArray'];

		/* process status */

		foreach ($statusArray as $key => $valueArray)
		{
			if (array_key_exists($valueArray['status'], $wordingArray))
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

	protected function _getStatusArray()
	{
		$driverArray = $this->_registry->get('driverArray');
		$moduleArray = $this->_registry->get('moduleArray');
		$testOsArray =
		[
			'linux',
			'windows nt'
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
				'status' => version_compare($this->_registry->get('phpVersion'), '5.4', '>') ? 1 : 0
			],
			'PDO' =>
			[
				'value' => implode($driverArray, ', '),
				'status' => count($driverArray) ? 1 : 0
			],
			'SESSION' =>
			[
				'value' => $this->_registry->get('sessionStatus'),
				'status' => $this->_registry->get('sessionStatus') ? 1 : 0
			]
		];

		/* process optional */

		if ($moduleArray)
		{
			foreach ($testModuleArray as $value)
			{
				$statusArray[$value] =
				[
					'value' => null,
					'status' => in_array($value, $moduleArray) ? 1 : 0
				];
			}
		}
		return $statusArray;
	}
}