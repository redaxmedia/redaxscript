<?php
namespace Redaxscript\Server;

/**
 * children class to get host
 *
 * @since 2.4.0
 *
 * @category Redaxscript
 * @package Server
 * @author Henry Ruhs
 */

class Host extends Server
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
		$output = $this->_request->get('HTTP_HOST');
		return $output;
	}
}