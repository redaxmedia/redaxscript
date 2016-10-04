<?php
namespace Redaxscript\Server;

/**
 * children class to get directory
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Server
 * @author Henry Ruhs
 */

class Directory extends ServerAbstract
{
	/**
	 * get the output
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getOutput()
	{
		$output = dirname($this->_request->getServer('SCRIPT_NAME'));
		return $output;
	}
}