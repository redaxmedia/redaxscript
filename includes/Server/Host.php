<?php
namespace Redaxscript\Server;

/**
 * children class to get the host
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Server
 * @author Henry Ruhs
 */

class Host extends ServerAbstract
{
	/**
	 * get the output
	 *
	 * @since 2.4.0
	 *
	 * @return string|boolean
	 */

	public function getOutput()
	{
		$output = $this->_request->getServer('HTTP_HOST');
		return $output;
	}
}
