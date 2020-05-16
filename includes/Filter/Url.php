<?php
namespace Redaxscript\Filter;

use function filter_var;
use function strtolower;

/**
 * children class to filter the url
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Url implements FilterInterface
{
	/**
	 * sanitize the url
	 *
	 * @since 2.2.0
	 *
	 * @param string $url url to be sanitized
	 *
	 * @return string
	 */

	public function sanitize(string $url = null) : string
	{
		return filter_var(strtolower($url), FILTER_SANITIZE_URL);
	}
}
