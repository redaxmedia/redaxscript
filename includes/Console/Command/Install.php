<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\CommandAbstract;

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
		'name' => 'Install',
		'command' => 'install',
		'author' => 'Redaxmedia',
		'description' => 'Install the database',
		'version' => '3.0.0'
	);
	
	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		//php console install
		//php console install --module=HelloWorld
	}
}