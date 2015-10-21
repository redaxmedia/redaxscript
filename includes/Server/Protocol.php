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
		$output = $this->_request->getServer('HTTPS') === 'off' || empty($this->_request->getServer('HTTPS')) ? 'http' : 'https';
		return $output;
	}
}