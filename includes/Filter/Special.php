<?php
namespace Redaxscript\Filter;

/**
 * children class to filter the special
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Special implements FilterInterface
{
	/**
	 * sanitize the special
	 *
	 * @since 3.0.0
	 *
	 * @param string $special target with special character
	 *
	 * @return string
	 */

	public function sanitize($special = null)
	{
		return preg_replace('/[^a-zA-Z0-9]/i', null, $special);
	}
}
