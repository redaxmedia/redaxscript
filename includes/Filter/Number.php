<?php
namespace Redaxscript\Filter;

use function filter_var;

/**
 * children class to filter the number
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Number implements FilterInterface
{
	/**
	 * sanitize the number
	 *
	 * @since 4.0.0
	 *
	 * @param int|string $number number to be sanitized
	 *
	 * @return int|null
	 */

	public function sanitize($number = null) : ?int
	{
		return (int)filter_var($number, FILTER_SANITIZE_NUMBER_INT) ? : null;
	}
}
