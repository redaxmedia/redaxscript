<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;

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
	 * backup the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return string
	 */

	protected function _database($optionArray = array())
	{
		$dbType = $this->_config->get('dbType');
		$dbHost = $this->_config->get('dbHost');
		$dbName = $this->_config->get('dbName');
		$dbUser = $this->_config->get('dbUser');
		$dbPassword = $this->_config->get('password');
		$path = $this->prompt('path', $optionArray);
		if (is_dir($path))
		{
			$command = null;
			if ($dbType === 'mysql' && $dbName && $dbName && $dbUser)
			{
				$command = 'mysqldump -u ' . $dbUser . ' -p' . $dbPassword . ' ' . $dbName;
			}
			if ($dbType === 'pgsql' && $dbName)
			{
				$command = 'pg_dump ' . $dbName;
			}
			if ($dbType === 'sqlite' && $dbHost)
			{
				$command = 'sqlite3 ' . $dbHost . ' .dump';
			}
			$command .= ' > ' . $path . '/backup.' . $dbType;
			return shell_exec($command);
		}
		return false;
	}
}