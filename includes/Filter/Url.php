<?php
namespace Redaxscript\Filter;

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
	 * @param string $url url address
	 *
	 * @return string
	 */

	public function sanitize($url = null)
	{
		return filter_var(strtolower($url), FILTER_SANITIZE_URL);
	}
}