<?php
namespace Redaxscript\Filter;

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
	 * @param string $boolean boolean value
	 *
	 * @return mixed
	 */

	public function sanitize($boolean = null)
	{
		return filter_var($boolean, FILTER_VALIDATE_BOOLEAN,
		[
			'flags' => FILTER_NULL_ON_FAILURE
		]);
	}
}