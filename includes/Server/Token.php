<?php
namespace Redaxscript\Server;

use function sha1;

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
		return sha1($this->_request->getCookie('PHPSESSID') . $this->_request->getServer('REMOTE_ADDR') . $this->_request->getServer('HTTP_USER_AGENT') . $host->getOutput());
	}
}
