<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * abstract class to create a bootstrap class
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 *
 * @method protected autorun()
 */

abstract class BootstrapAbstract implements BootstrapInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * constructor of the class
	 *
	 * @since 3.1.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 */

	public function __construct(Registry $registry, Request $request)
	{
		$this->_registry = $registry;
		$this->_request = $request;
		$this->autorun();
	}
}
