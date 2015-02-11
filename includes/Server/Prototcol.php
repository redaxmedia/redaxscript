<?php
namespace Redaxscript\Server;

/**
 * children class to get protocol
 *
 * @since 2.4.0
 *
 * @category Redaxscript
 * @package Server
 * @author Henry Ruhs
 */

class Protocol extends Server
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
		$output = $this->_request->getServer('HTTPS') ? 'https' : 'http';
		return $output;
	}
}