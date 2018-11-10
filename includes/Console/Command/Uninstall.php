<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Db;
use Redaxscript\Installer;

/**
 * children class to execute the uninstall command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Uninstall extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'uninstall' =>
		[
			'description' => 'Uninstall command',
			'argumentArray' =>
			[
				'database' =>
				[
					'description' => 'Uninstall the database',
					'optionArray' =>
					[
						'halt-on-error' =>
						[
							'description' => 'Halt on error'
						]
					]
				],
				'module' =>
				[
					'description' => 'Uninstall the module',
					'optionArray' =>
					[
						'alias' =>
						[
							'description' => 'Required module alias'
						],
						'halt-on-error' =>
						[
							'description' => 'Halt on error'
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
			return $this->_database() ? $this->success() : $this->error($haltOnError);
		}
		if ($argumentKey === 'module')
		{
			return $this->_module($parser->getOption()) ? $this->success() : $this->error($haltOnError);
		}
		return $this->getHelp();
	}

	/**
	 * install the database
	 *
	 * @since 3.0.0
	 *
	 * @return bool
	 */

	protected function _database() : bool
	{
		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->rawDrop();
		return Db::getStatus() === 1;
	}

	/**
	 * uninstall the module
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return bool
	 */

	protected function _module(array $optionArray = []) : bool
	{
		$alias = $this->prompt('alias', $optionArray);
		$moduleClass = 'Redaxscript\Modules\\' . $alias . '\\' . $alias;

		/* uninstall */

		if (class_exists($moduleClass))
		{
			$module = new $moduleClass($this->_registry, $this->_request, $this->_language, $this->_config);
			return $module->uninstall();
		}
		return false;
	}
}
