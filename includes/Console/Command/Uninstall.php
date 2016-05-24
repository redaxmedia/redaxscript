<?php
namespace Redaxscript\Console\Command;

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
	 * @return string
	 */

	public function run()
	{
		return $this->getHelp();
	}
}
