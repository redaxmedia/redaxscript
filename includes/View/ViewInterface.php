<?php
namespace Redaxscript\View;

/**
 * interface to define a view
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

interface ViewInterface
{
	/**
	 * stringify the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString();

	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render();
}
