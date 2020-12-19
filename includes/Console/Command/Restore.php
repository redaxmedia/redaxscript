<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Installer;
use function exec;
use function is_file;

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
			return $this->_database($parser->getOptionArray()) ? $this->success() : $this->error($haltOnError);
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
	 * @return bool
	 */

	protected function _database(array $optionArray = []) : bool
	{
		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$dbType = $this->_config->get('dbType');
		$dbHost = $this->_config->get('dbHost');
		$dbName = $this->_config->get('dbName');
		$dbUser = $this->_config->get('dbUser');
		$dbPassword = $this->_config->get('dbPassword');
		$directory = $this->prompt('directory', $optionArray);
		$file = $this->prompt('file', $optionArray);
		$path = $directory . DIRECTORY_SEPARATOR . $file;

		/* restore */

		if (is_file($path))
		{
			if ($dbType === 'mysql' && $dbHost && $dbName && $dbUser && $dbPassword)
			{
				$command = 'mysql --host=' . $dbHost . ' --user=' . $dbUser . ' --password=' . $dbPassword . ' ' . $dbName . ' < ' . $path;
			}
			if ($dbType === 'pgsql' && $dbHost && $dbName && $dbUser && $dbPassword)
			{
				$command = 'psql postgres://' . $dbUser . ':' . $dbPassword . '@' . $dbHost . '/' . $dbName . ' < ' . $path;
			}
			if ($dbType === 'sqlite' && $dbHost)
			{
				$command = 'sqlite3 ' . $dbHost . ' .dump < ' . $path;
			}
			if ($command)
			{
				$installer->rawDrop();
				exec($command, $outputArray, $status);
			}
			return $status === 0;
		}
		return false;
	}
}
