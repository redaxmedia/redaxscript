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
						'directory' => array(
							'description' => 'Required directory'
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
		$dbPassword = $this->_config->get('dbPassword');
		$directory = $this->prompt('directory', $optionArray);
		$date = date('Y_m_d_H:i:s');
		$file = $dbName ? $dbName . '_' . $date . '.' . $dbType : $date . '.' . $dbType;
		if (is_dir($directory) || mkdir($directory))
		{
			if ($dbType === 'mysql' && $dbName && $dbName && $dbUser)
			{
				$command = 'mysqldump -u ' . $dbUser . ' -p' . $dbPassword . ' ' . $dbName . ' > ' . $directory . '/' . $file;
			}
			if ($dbType === 'pgsql' && $dbName)
			{
				$command = 'PGPASSWORD=' . $dbPassword . ' pg_dump -U postgres -h ' . $dbHost . ' ' . $dbName . ' > ' . $directory . '/' . $file;
			}
			if ($dbType === 'sqlite' && $dbHost)
			{
				$command = 'sqlite3 ' . $dbHost . ' .dump > ' . $directory . '/' . $file;
			}
			exec($command, $output, $error);
			return $error === 0;
		}
		return false;
	}
}