<?php
namespace Redaxscript\Server;

/**
 * children class to get directory
 *
 * @since 2.4.0
 *
 * @category Redaxscript
 * @package Server
 * @author Henry Ruhs
 */

class Directory extends Server
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
		$output = dirname($this->_request->get('SCRIPT_NAME'));
		return $output;
	}
}