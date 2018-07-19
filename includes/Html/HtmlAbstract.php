<?php
namespace Redaxscript\Html;

/**
 * abstract class to create html
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Html
 * @author Henry Ruhs
 */

abstract class HtmlAbstract
{
	/**
	 * value of the html
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
	 * @return self
	 */

	public function html(string $html = null) : self
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
	 * @return self
	 */

	public function append(string $html = null) : self
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
	 * @return self
	 */

	public function prepend(string $html = null) : self
	{
		$this->_html = $html . $this->_html;
		return $this;
	}

	/**
	 * clear the html
	 *
	 * @since 3.0.0
	 *
	 * @return self
	 */

	public function clear() : self
	{
		$this->html(null);
		return $this;
	}
}
