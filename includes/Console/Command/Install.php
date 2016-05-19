<?php
namespace Redaxscript\Console\Command;

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
						'<name>' => array(
							'description' => 'Required module <name>'
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
	 */

	public function run()
	{
		return $this->getHelp();
	}
}