<?php
namespace Redaxscript\Console\Command;

/**
 * children class to execute the status command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Status extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = array(
		'status' => array(
			'description' => 'Status command',
			'argumentArray' => array(
				'database' => array(
					'description' => 'Show database status'
				),
				'system' => array(
					'description' => 'Show system requirements'
				)
			)
		)
	);
	
	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		return $this->getHelp();
	}
}