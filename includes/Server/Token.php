<?php
namespace Redaxscript\Server;

/**
 * children class to get the token
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Server
 * @author Henry Ruhs
 */

class Token extends ServerAbstract
{
	/**
	 * get the output
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getOutput() : string
	{
		$host = new Host($this->_request);
		$output = sha1(session_id() . $this->_request->getServer('REMOTE_ADDR') . $this->_request->getServer('HTTP_USER_AGENT') . $host->getOutput());
		return $output;
	}
}