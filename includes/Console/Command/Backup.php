<?php
namespace Redaxscript\Console\Command;

use PDO;
use Redaxscript\Console\Parser;
use Redaxscript\Db;

/**
 * children class to execute the backup command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Backup extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = array(
		'backup' => array(
			'description' => 'Backup command',
			'argumentArray' => array(
				'database' => array(
					'description' => 'Backup the database',
					'optionArray' => array(
						'path' => array(
							'description' => 'Path of the backup'
						)
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
			return $this->_database($parser->getOption());
		}
		return $this->getHelp();
	}

	/**
	 * backup database
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return string
	 */

	protected function _database($optionArray = array())
	{
		$path = $this->prompt('path', $optionArray);
		if (is_dir($path))
		{
			//exec('cp test.sql backup.sql');
			//exec('mysqldump -u root -ptest test < backup.mysql');
			//exec('pg_dump test > backup.pgsql');
			return true;
		}
		return false;
	}
}