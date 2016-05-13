<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\CommandAbstract;

/**
 * children class to execute the reinstall command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Reinstall extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = array(
		'name' => 'Reinstall',
		'command' => 'reinstall',
		'author' => 'Redaxmedia',
		'description' => 'Reinstall the database',
		'version' => '3.0.0'
	);
	
	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		//php console reinstall
	}
}