<?php
namespace Redaxscript\Controller;

use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * abstract class to create a controller class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

abstract class ControllerAbstract implements ControllerInterface
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
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Request $request, Language $language)
	{
		$this->_registry = $registry;
		$this->_request = $request;
		$this->_language = $language;
	}
}