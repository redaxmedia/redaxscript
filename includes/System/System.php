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
	 * get server information
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function get();
}
