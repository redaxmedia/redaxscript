<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Db;
use Redaxscript\Console\CommandAbstract;

/**
 * children class to execute the setting command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Setting extends CommandAbstract
{
	/**
	 * run the command
	 *
	 * @since 3.0.0
	 */

	public function run()
	{
		$commandArgument = $this->_parser->getArgument(2);
		if ($commandArgument)
		{
			return Db::getSetting($commandArgument) . PHP_EOL;
		}		
		//php console setting show
		//php console setting set --name=value
	}
}
