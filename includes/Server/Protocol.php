<?php
namespace Redaxscript\Server;

/**
 * children class to get the protocol
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Server
 * @author Henry Ruhs
 */

class Protocol extends ServerAbstract
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
		$output = $this->_request->getServer('HTTPS') === 'off' || !$this->_request->getServer('HTTPS') ? 'http' : 'https';
		return $output;
	}
}