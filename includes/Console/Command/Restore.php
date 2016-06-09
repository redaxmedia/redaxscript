<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;

/**
 * children class to execute the restore command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Restore extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = array(
		'restore' => array(
			'description' => 'Restore command',
			'argumentArray' => array(
				'database' => array(
					'description' => 'Restore the database',
					'optionArray' => array(
						'directory' => array(
							'description' => 'Required directory'
						),
						'file' => array(
							'description' => 'Required file'
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
	 * restore the database
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
		$file = $this->prompt('file', $optionArray);
		if (is_dir($directory))
		{
			if ($dbType === 'mysql' && $dbName && $dbName && $dbUser)
			{
				$command = 'mysql -u ' . $dbUser . ' -p' . $dbPassword . ' ' . $dbName . ' < ' . $directory . '/' . $file . ' 2>/dev/null';
			}
			if ($dbType === 'pgsql' && $dbName)
			{
				$command = 'cat ' . $directory . '/' . $file . ' | PGPASSWORD=' . $dbPassword . ' psql -U postgres -h ' . $dbHost . ' -d ' . $dbName . ' 2>/dev/null';
			}
			if ($dbType === 'sqlite' && $dbHost)
			{
				$command = 'sqlite3 ' . $dbHost . ' < ' . $directory . '/' . $file . ' 2>/dev/null';
			}
			exec($command, $output, $error);
			return $error === 0;
		}
		return false;
	}
}