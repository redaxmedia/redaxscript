<?php
namespace Redaxscript\Router;

use Redaxscript\Config;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * abstract class to create a router class
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Router
 * @author Henry Ruhs
 */

class RouterAbstract extends Parameter
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * constructor of the class
	 *
	 * @since 3.3.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 * @param Config $config instance of the config class
	 */

	public function __construct(Registry $registry, Request $request, Language $language, Config $config)
	{
		parent::__construct($request);
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_config = $config;
	}
}