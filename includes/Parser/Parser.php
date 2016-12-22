<?php
namespace Redaxscript\Parser;

use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * parent class to parse content for pseudo tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Parser
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
	 * output of the parser
	 *
	 * @var string
	 */

	protected $_output;

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $content content to be parsed
	 */

	public function init($content = null)
	{
		$this->_output = $content;
	}

	/**
	 * get the output
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function getOutput()
	{
		return $this->_output;
	}
}