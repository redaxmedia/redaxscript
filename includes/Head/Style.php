<?php
namespace Redaxscript\Head;

use Redaxscript\Html;
use Redaxscript\Singleton;

/**
 * children class to create the style tag
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 */

class Style extends Singleton implements HeadInterface
{
	/**
	 * inline style
	 *
	 * @var string
	 */

	protected $_inline = null;

	/**
	 * stringify the style
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->render();
	}

	/**
	 * append inline style
	 *
	 * @since 3.0.0
	 *
	 * @param string $inline
	 *
	 * @return Style
	 */

	public function appendInline($inline = null)
	{
		$this->_inline .= $inline;
		return $this;
	}

	/**
	 * prepend inline style
	 *
	 * @since 3.0.0
	 *
	 * @param string $inline
	 *
	 * @return Style
	 */

	public function prependInline($inline = null)
	{
		$this->_inline = $inline . $this->_inline;
		return $this;
	}

	/**
	 * render the style
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = null;

		/* html elements */

		$styleElement = new Html\Element();
		$styleElement->init('style');

		/* collect inline */

		if ($this->_inline)
		{
			$output .= $styleElement
				->copy()
				->text($this->_inline);
		}
		$this->clear();
		return $output;
	}

	/**
	 * clear the style
	 *
	 * @since 3.0.0
	 *
	 * @return Style
	 */

	public function clear()
	{
		$this->_inline = null;
		return $this;
	}
}