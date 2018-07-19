<?php
namespace Redaxscript\Content;

use Redaxscript\Config;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * abstract class to create a parser class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

abstract class ParserAbstract
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
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * array of tag namespaces
	 *
	 * @var array
	 */

	protected $_namespaceArray =
	[
		'Redaxscript\Content\Tag\Code',
		'Redaxscript\Content\Tag\Language',
		'Redaxscript\Content\Tag\Module',
		'Redaxscript\Content\Tag\More',
		'Redaxscript\Content\Tag\Registry',
		'Redaxscript\Content\Tag\Template'
	];

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 * @param Config $config instance of the config class
	 */

	public function __construct(Registry $registry, Request $request, Language $language, Config $config)
	{
		$this->_registry = $registry;
		$this->_request = $request;
		$this->_language = $language;
		$this->_config = $config;
	}
}