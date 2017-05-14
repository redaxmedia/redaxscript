<?php
namespace Redaxscript\Server;

/**
 * children class to get the root
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Server
 * @author Henry Ruhs
 */

class Root extends ServerAbstract
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

		/* collect output */

		$output = $protocol->getOutput() . '://' . $host->getOutput();
		if ($directory->getOutput() !== DIRECTORY_SEPARATOR)
		{
			$output .= $directory->getOutput();
		}
		return $output;
	}
}

