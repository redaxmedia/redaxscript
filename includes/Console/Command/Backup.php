<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Console\CommandAbstract;

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