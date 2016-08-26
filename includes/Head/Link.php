<?php
namespace Redaxscript\Head;

use Redaxscript\Html;

/**
 * children class to process the meta link request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Link extends CollectorAbstract
{
	/**
	 * generate html element
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return string
	 */

	public function generate($key = null, $value = null)
	{
		/* html elements */

		$linkElement = new Html\Element();

		$linkElement->init('link',
			[
				'href' => $key,
				'rel' => $value
			]);

		return $linkElement;
	}

	/**
	 * before the other meta tags
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string $value
	 */

	public function append($key = null, $value = null)
	{
		$this->_html .= $this->generate($key, $value);
	}

	/**
	 * after the other meta tags
	 *
	 * @since 3.0.0
	 *
	 * @param string $key
	 * @param string $value
	 */

	public function prepend($key = null, $value = null)
	{
		$this->_html = $this->generate($key, $value) . $this->_html;
	}
}