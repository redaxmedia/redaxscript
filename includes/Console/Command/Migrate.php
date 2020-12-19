<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Installer;
use Redaxscript\Model;

/**
 * children class to execute the migrate command
 *
 * @since 4.4.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Migrate extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'migrate' =>
		[
			'description' => 'Migrate command',
			'argumentArray' =>
			[
				'database' =>
				[
					'description' => 'Migrate the database',
				]
			]
		]
	];

	/**
	 * run the command
	 *
	 * @since 4.4.0
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
			return $this->_database() ? $this->success() : $this->error($haltOnError);
		}
		return $this->getHelp();
	}

	/**
	 * migrate the database
	 *
	 * @since 4.4.0
	 *
	 * @return bool
	 */

	protected function _database() : bool
	{
		$settingModel = new Model\Setting();
		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		return $installer->rawMigrate($settingModel->get('version'));
	}
}
