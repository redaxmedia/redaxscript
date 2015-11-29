<?php
namespace Redaxscript\View;

/**
 * interface to define a view
 *
 * @since 3.0.0
 *
 * @category Redaxscript
 * @package View
 * @author Henry Ruhs
 */

interface ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render();
}
