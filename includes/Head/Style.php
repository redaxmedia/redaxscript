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

	protected static $_inline = null;

	/**
	 * stringify the style
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString() : string
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
	 * @return self
	 */

	public function appendInline(string $inline = null) : self
	{
		self::$_inline .= $inline;
		return $this;
	}

	/**
	 * prepend inline style
	 *
	 * @since 3.0.0
	 *
	 * @param string $inline
	 *
	 * @return self
	 */

	public function prependInline(string $inline = null) : self
	{
		self::$_inline = $inline . self::$_inline;
		return $this;
	}

	/**
	 * render the style
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = null;

		/* html elements */

		$styleElement = new Html\Element();
		$styleElement->init('style');

		/* collect inline */

		if (self::$_inline)
		{
			$output .= $styleElement
				->copy()
				->text(self::$_inline);
		}
		$this->clear();
		return $output;
	}

	/**
	 * clear the style
	 *
	 * @since 3.0.0
	 *
	 * @return self
	 */

	public function clear() : self
	{
		self::$_inline = null;
		return $this;
	}
}