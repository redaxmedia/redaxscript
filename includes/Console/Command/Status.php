<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Db;
use Redaxscript\Console\CommandAbstract;

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
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		return Db::getStatus() . PHP_EOL;
	}
}