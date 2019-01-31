<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Dater;
use Redaxscript\Filesystem;
use function exec;
use function implode;
use function is_dir;
use function is_string;
use function mkdir;

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

	protected $_commandArray =
	[
		'backup' =>
		[
			'description' => 'Backup command',
			'argumentArray' =>
			[
				'database' =>
				[
					'description' => 'Backup the database',
					'optionArray' =>
					[
						'directory' =>
						[
							'description' => 'Required directory'
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
	 * @return string|null
	 */

	public function run(string $mode = null) : ?string
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$argumentKey = $parser->getArgument(1);
		$haltOnError = (bool)$parser->getOption('halt-on-error');
		if ($argumentKey === 'database')
		{
			return $this->_database($parser->getOption()) ? $this->success() : $this->error($haltOnError);
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
	 * @return bool
	 */

	protected function _database(array $optionArray = []) : bool
	{
		$dater = new Dater();
		$dater->init();
		$dbType = $this->_config->get('dbType');
		$dbHost = $this->_config->get('dbHost');
		$dbName = $this->_config->get('dbName');
		$dbUser = $this->_config->get('dbUser');
		$dbPassword = $this->_config->get('dbPassword');
		$directory = $this->prompt('directory', $optionArray);
		$file = $dbName ? $dbName . '_' . $dater->formatDate() . '_' . $dater->formatTime() . '.' . $dbType : $dater->formatDate() . '_' . $dater->formatTime() . '.' . $dbType;

		/* backup filesystem */

		$backupFilesystem = new Filesystem\Directory();
		$backupFilesystem->init($directory);

		/* backup */

		if (is_dir($directory) || is_string($directory) && mkdir($directory))
		{
			$command = null;
			if ($dbType === 'mysql' && $dbHost && $dbName && $dbUser && $dbPassword)
			{
				$command = 'mysqldump --host=' . $dbHost . ' --user=' . $dbUser . ' --password=' . $dbPassword . ' ' . $dbName;
			}
			if ($dbType === 'pgsql' && $dbHost && $dbName && $dbUser && $dbPassword)
			{
				$command =  'pg_dump --host=' . $dbHost . ' --username=' . $dbUser . ' --password='. $dbPassword . ' ' . $dbName;
			}
			if ($dbType === 'sqlite' && $dbHost)
			{
				$command =  'cat ' . $dbHost;
			}
			exec($command, $outputArray, $error);
			$content = implode($outputArray, PHP_EOL);
			return $error === 0 && $backupFilesystem->writeFile($file, $content);
		}
		return false;
	}
}
