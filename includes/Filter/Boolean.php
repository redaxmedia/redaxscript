<?php
namespace Redaxscript\Filter;

use function filter_var;

/**
 * children class to filter the boolean
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Boolean implements FilterInterface
{
	/**
	 * sanitize the boolean
	 *
	 * @since 3.0.0
	 *
	 * @param string $boolean
	 *
	 * @return bool
	 */

	public function sanitize(string $boolean = null) : bool
	{
		return filter_var($boolean, FILTER_VALIDATE_BOOLEAN);
	}
}
