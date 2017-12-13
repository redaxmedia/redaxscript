<?php
namespace Redaxscript\Head;

/**
 * interface to define a head class
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Head
 * @author Henry Ruhs
 */

interface HeadInterface
{
	/**
	 * render the tag
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function render();
}
