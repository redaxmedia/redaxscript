<?php
namespace Redaxscript\Html;

/**
 * abstract class to generate html
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Form
 * @author Henry Ruhs
 */

abstract class HtmlAbstract
{
	/**
	 * html of the element
	 *
	 * @var string
	 */

	protected $_html;

	/**
	 * set the html
	 *
	 * @since 2.6.0
	 *
	 * @param string $html html to set
	 *
	 * @return Element
	 */

	public function html($html = null)
	{
		$this->_html = $html;
		return $this;
	}

	/**
	 * append to the html
	 *
	 * @since 2.6.0
	 *
	 * @param string $html html to append
	 *
	 * @return Element
	 */

	public function append($html = null)
	{
		$this->_html .= $html;
		return $this;
	}

	/**
	 * prepend to the html
	 *
	 * @since 2.6.0
	 *
	 * @param string $html html to prepend
	 *
	 * @return Element
	 */

	public function prepend($html = null)
	{
		$this->_html = $html . $this->_html;
		return $this;
	}

	/**
	 * wrap the html
	 *
	 * @since 2.6.0
	 *
	 * @param string $tag tag of the wrap
	 *
	 * @return Element
	 */

	public function wrap($tag = null)
	{
		if ($tag && $this->_html)
		{
			$this->_html = '<' . $tag . '>' . $this->_html . '</' . $tag . '>';
		}
		return $this;
	}

	/**
	 * clean the html
	 *
	 * @since 2.6.0
	 *
	 * @return Element
	 */

	public function clean()
	{
		$this->html(null);
		return $this;
	}
}