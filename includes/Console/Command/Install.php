<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\Parser;
use Redaxscript\Db;
use Redaxscript\Installer;

/**
 * children class to execute the install command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Install extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'install' =>
		[
			'description' => 'Install command',
			'argumentArray' =>
			[
				'database' =>
				[
					'description' => 'Install the database',
					'optionArray' =>
					[
						'admin-name' =>
						[
							'description' => 'Required admin name'
						],
						'admin-user' =>
						[
							'description' => 'Required admin user'
						],
						'admin-password' =>
						[
							'description' => 'Required admin password'
						],
						'admin-email' =>
						[
							'description' => 'Required admin email'
						],
						'halt-on-error' =>
						[
							'description' => 'Halt on error'
						]
					]
				],
				'module' =>
				[
					'description' => 'Install the module',
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
			return $this->_database($parser->getOption()) ? $this->success() : $this->error($haltOnError);
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
	 * @param array $optionArray
	 *
	 * @return bool
	 */

	protected function _database(array $optionArray = []) : bool
	{
		$adminName = $this->prompt('admin-name', $optionArray);
		$adminUser = $this->prompt('admin-user', $optionArray);
		$adminPassword = $this->prompt('admin-password', $optionArray);
		$adminEmail = $this->prompt('admin-email', $optionArray);

		/* install */

		if ($adminName && $adminUser && $adminPassword && $adminEmail)
		{
			$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
			$installer->init();
			$installer->rawCreate();
			$installer->insertData(
			[
				'adminName' => $adminName,
				'adminUser' => $adminUser,
				'adminPassword' => $adminPassword,
				'adminEmail' => $adminEmail
			]);
			return Db::getStatus() === 2;
		}
		return false;
	}

	/**
	 * install the module
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

		/* install */

		if (class_exists($moduleClass))
		{
			$module = new $moduleClass($this->_registry, $this->_request, $this->_language, $this->_config);
			return $module->install();
		}
		return false;
	}
}
