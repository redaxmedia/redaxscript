<?php
namespace Redaxscript\Filter;

/**
 * children class to filter alias
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Filter
 * @author Henry Ruhs
 */

class Alias implements FilterInterface
{
	/**
	 * sanitize the alias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias alias for routes and users
	 *
	 * @return string
	 */

	public function sanitize($alias = null)
	{
		$output = preg_replace('/\W+/i', ' ', $alias);
		$output = preg_replace('/\s+/i', '-', trim($output));
		return $output;
	}
}