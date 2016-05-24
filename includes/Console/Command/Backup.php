<?php
namespace Redaxscript\Console\Command;

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

	protected $_commandArray = array(
		'backup' => array(
			'description' => 'Backup command',
			'argumentArray' => array(
				'database' => array(
					'description' => 'Backup the database',
					'optionArray' => array(
						'email' => array(
							'description' => 'Required email to send the backup file'
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