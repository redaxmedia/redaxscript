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
		'name' => 'Uninstall',
		'command' => 'uninstall',
		'author' => 'Redaxmedia',
		'description' => 'Uninstall the database',
		'version' => '3.0.0'
	);

	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		//php console uninstall
		//php console uninstall --module=HelloWorld
	}
}
