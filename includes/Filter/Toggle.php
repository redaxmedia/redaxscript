<?php
namespace Redaxscript\Filter;

/**
 * children class to filter the toggle
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Toggle implements FilterInterface
{
	/**
	 * sanitize the $toggle
	 *
	 * @since 4.2.0
	 *
	 * @param string $toggle
	 *
	 * @return int
	 */

	public function sanitize(string $toggle = null) : int
	{
		return $toggle === 'on' ? 1 : 0;
	}
}
