<?php
namespace Redaxscript\Content;

/**
 * parent class to parse content for pseudo tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

class Parser extends ParserAbstract
{
	/**
	 * output of the parser
	 *
	 * @var string
	 */

	protected $_output;

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $content content to be parsed
	 * @param string $route route of the content
	 */

	public function process(string $content = null, string $route = null) : void
	{
		$this->_output = $content;

		/* process */

		foreach ($this->_namespaceArray as $tagClass)
		{
			$tag = new $tagClass($this->_registry, $this->_request, $this->_language, $this->_config);
			$this->_output = $tag->process($this->_output, $route);
		}
	}

	/**
	 * get the output
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function getOutput() : string
	{
		return $this->_output;
	}
}