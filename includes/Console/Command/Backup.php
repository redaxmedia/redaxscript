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
		'name' => 'Backup',
		'command' => 'backup',
		'author' => 'Redaxmedia',
		'description' => 'Create a database backup',
		'version' => '3.0.0'
	);

	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		//php console.php backup
		//php console.php backup --email=alternative@email.com
		//php console.php backup --path=path/to/store/backup
	}
}