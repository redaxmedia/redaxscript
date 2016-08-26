<?php
namespace Redaxscript\Head;

use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * abstract class to create a head class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Balázs Szilágyi
 */

abstract class CollectorAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * container that will be rendered
	 *
	 * @var array
	 */

	protected $_html;

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param Request $request instance of the request class
	 */

	public function __construct(Registry $registry, Language $language, Request $request)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_request = $request;
	}

	/**
	 * override ToString
	 *
	 * @since 3.0.0
	 */

	public function __toString()
	{
		return $this->_html;
	}

	/**
	 * prepend functionality
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string $value
	 */

	public function generate($key = null, $value = null)
	{
	}

	/**
	 * append functionality
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string $value
	 */

	public function append($key = null, $value = null)
	{
	}

	/**
	 * prepend functionality
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string $value
	 */

	public function prepend($key = null, $value = null)
	{
	}
}