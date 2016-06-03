<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Db;
use Redaxscript\Console\Parser;
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

	protected $_commandArray = array(
		'uninstall' => array(
			'description' => 'Uninstall command',
			'argumentArray' => array(
				'database' => array(
					'description' => 'Uninstall the database'
				),
				'module' => array(
					'description' => 'Uninstall the module',
					'optionArray' => array(
						'alias' => array(
							'description' => 'Required module alias'
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
			return $this->_database();
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
	 * @return boolean
	 */

	protected function _database()
	{
		$installer = new Installer($this->_config);
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
	 * @return boolean
	 */

	protected function _module($optionArray = array())
	{
		$alias = $this->prompt('alias', $optionArray);
		$moduleClass = 'Redaxscript\\Modules\\' . $alias . '\\' . $alias;
		if (class_exists($moduleClass))
		{
			$module = new $moduleClass;
			$module->uninstall();
			return !Db::forTablePrefix('modules')->where('alias', $alias)->count();
		}
		return false;
	}
}
