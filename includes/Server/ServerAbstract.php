<?php
namespace Redaxscript\Server;

use Redaxscript\Request;

/**
 * abstract class to create a server class
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Server
 * @author Henry Ruhs
 */

abstract class ServerAbstract implements ServerInterface
{
	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Request $request instance of the request class
	 */

	public function __construct(Request $request)
	{
		$this->_request = $request;
	}
}
