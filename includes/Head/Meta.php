<?php
namespace Redaxscript\Head;

use Redaxscript\Html;

/**
 * children class to process the meta request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Meta extends CollectorAbstract
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

		$metaElement = new Html\Element();

		if ($key == 'charset')
		{
			$metaElement->init('meta',
				[
					'charset' => $value
				]);
		}
		else
		{
			$metaElement->init('meta',
				[
					'name' => $key,
					'content' => $value
				]);
		}

		return $metaElement;
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