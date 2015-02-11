<?php
namespace Redaxscript\Server;

/**
 * children class to get root
 *
 * @since 2.4.0
 *
 * @category Redaxscript
 * @package Server
 * @author Henry Ruhs
 */

class Root extends Server
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
		$protocol = new Protocol($this->_request);
		$host = new Host($this->_request);
		$directory = new Directory($this->_request);

		/* collect  output */

		$output = $protocol->getOutput() . $host->getOutput();
		if ($directory->getOutput() === '/' || $directory->getOutput() === '\\')
		{
			$output .= $directory->getOutput();
		}
		return $output;
	}
}