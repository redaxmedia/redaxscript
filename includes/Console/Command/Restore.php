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

	protected $_commandArray =
	[
		'restore' =>
		[
			'description' => 'Restore command',
			'argumentArray' =>
			[
				'database' =>
				[
					'description' => 'Restore the database',
					'optionArray' =>
					[
						'directory' =>
						[
							'description' => 'Required directory'
						],
						'file' =>
						[
							'description' => 'Required file'
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

	protected function _database($optionArray = [])
	{
		$dbType = $this->_config->get('dbType');
		$dbHost = $this->_config->get('dbHost');
		$dbName = $this->_config->get('dbName');
		$dbUser = $this->_config->get('dbUser');
		$dbPassword = $this->_config->get('dbPassword');
		$directory = $this->prompt('directory', $optionArray);
		$file = $this->prompt('file', $optionArray);

		/* restore */

		if (is_file($directory . DIRECTORY_SEPARATOR . $file))
		{
			if ($dbType === 'mysql' && $dbHost && $dbName && $dbUser)
			{
				$command = 'mysql -u ' . $dbUser . ' -p' . $dbPassword . ' -h ' . $dbHost . ' ' . $dbName . ' < ' . $directory . DIRECTORY_SEPARATOR . $file;
			}
			if ($dbType === 'pgsql' && $dbHost & $dbName)
			{
				$command = 'cat ' . $directory . DIRECTORY_SEPARATOR . $file . ' | PGPASSWORD=' . $dbPassword . ' psql -U postgres -h ' . $dbHost . ' -d ' . $dbName;
			}
			if ($dbType === 'sqlite' && $dbHost)
			{
				$command = 'sqlite3 ' . $dbHost . ' < ' . $directory . DIRECTORY_SEPARATOR . $file;
			}
			exec($command, $output, $error);
			return $error === 0 || $dbType === 'mssql';
		}
		return false;
	}
}