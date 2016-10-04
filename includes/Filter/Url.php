<?php
namespace Redaxscript\Filter;

/**
 * children class to filter url
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
	 * @param string $url target url address
	 *
	 * @return string
	 */

	public function sanitize($url = null)
	{
		$output = filter_var(strtolower($url), FILTER_SANITIZE_URL);
		return $output;
	}
}