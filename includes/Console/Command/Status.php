<?php
namespace Redaxscript\Console\Command;

use PDO;
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

	protected $_commandArray = array(
		'status' => array(
			'description' => 'Status command',
			'argumentArray' => array(
				'database' => array(
					'description' => 'Show database status',
					'wordingArray' => array(
						'No connection found',
						'Database tables missing',
						'Connection established'
					)
				),
				'system' => array(
					'description' => 'Show system requirements',
					'wordingArray' => array(
						'Failed',
						'Passed'
					)
				)
			)
		)
	);

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
		$driverArray = PDO::getAvailableDrivers();
		$moduleArray = function_exists('apache_get_modules') ? apache_get_modules() : array();
		$optionalArray = array(
			'mod_deflate',
			'mod_headers',
			'mod_rewrite'
		);
		$statusArray = array(
			'OS' => array(
				'value' => strtolower(php_uname('s')),
				'status' => strtolower(php_uname('s')) === 'linux' ? 1 : 0
			),
			'PHP' => array(
				'value' => phpversion(),
				'status' => version_compare(phpversion(), '5.3', '>') ? 1 : 0
			),
			'PDO' => array(
				'value' => implode($driverArray, ', '),
				'status' => count($driverArray) ? 1 : 0
			),
			'shell' => array(
				'value' => null,
				'status' => function_exists('shell') ? 1 : 0
			)
		);

		/* process optional */

		if ($moduleArray)
		{
			foreach ($optionalArray as $value)
			{
				$statusArray[$value] = array(
					'value' => null,
					'status' => in_array($value, $moduleArray) ? 1 : 0
				);
			}
		}
		return $statusArray;
	}
}