<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Db;
use Redaxscript\Console\Parser;
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

	protected $_commandArray = array(
		'install' => array(
			'description' => 'Install command',
			'argumentArray' => array(
				'database' => array(
					'description' => 'Install the database',
					'optionArray' => array(
						'admin-name' => array(
							'description' => 'Required admin name'
						),
						'admin-user' => array(
							'description' => 'Required admin user'
						),
						'admin-password' => array(
							'description' => 'Required admin password'
						),
						'admin-email' => array(
							'description' => 'Required admin email'
						)
					)
				),
				'module' => array(
					'description' => 'Install the module',
					'optionArray' => array(
						'<alias>' => array(
							'description' => 'Required module <alias>'
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
		if ($argumentKey === 'module')
		{
			return $this->_module($parser->getOption());
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
	 * @return boolean
	 */

	protected function _database($optionArray = array())
	{
		$adminName = $this->prompt('admin-name', $optionArray);
		$adminUser = $this->prompt('admin-user', $optionArray);
		$adminPassword = $this->prompt('admin-password', $optionArray);
		$adminEmail = $this->prompt('admin-email', $optionArray);
		if ($adminName && $adminUser && $adminPassword && $adminEmail)
		{
			$installer = new Installer($this->_config);
			$installer->init();
			$installer->rawCreate();
			$installer->insertData(array(
				'adminName' => $adminName,
				'adminUser' => $adminUser,
				'adminPassword' => $adminPassword,
				'adminEmail' => $adminEmail
			));
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
	 * @return boolean
	 */

	protected function _module($optionArray = array())
	{
		$alias = $this->prompt('alias', $optionArray);
		if ($alias)
		{
			$moduleClass = 'Redaxscript\\Modules\\' . $alias . '\\' . $alias;
			$module = new $moduleClass;
			$module->install();
			return Db::forTablePrefix('modules')->where('alias', $alias)->count() > 0;
		}
		return false;
	}
}