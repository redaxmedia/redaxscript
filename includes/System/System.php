<?php
namespace Redaxscript\Server;

/**
 * interface to define a server class
 *
 * @since 2.4.0
 *
 * @category Redaxscript
 * @package Server
 * @author Henry Ruhs
 */

interface Server
{
	/**
	 * get the output of the server
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getOutput();
}
