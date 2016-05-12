<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\CommandAbstract;

/**
 * children class to execute the config command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Config extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray = array(
		'name' => 'Config',
		'command' => 'config',
		'author' => 'Redaxmedia',
		'description' => 'Handle the configuration file',
		'version' => '3.0.0'
	);
	
	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		//php console.php config show
		//php console.php config write
	}
}
