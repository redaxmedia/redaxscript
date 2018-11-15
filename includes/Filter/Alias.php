<?php
namespace Redaxscript\Filter;

use function preg_replace;
use function trim;

/**
 * children class to filter the alias
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Alias implements FilterInterface
{
	/**
	 * sanitize the alias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias content and user alias
	 *
	 * @return string
	 */

	public function sanitize(string $alias = null) : string
	{
		$output = preg_replace('/[^a-zA-Z0-9]/i', ' ', $alias);
		$output = preg_replace('/\s+/i', '-', trim($output));
		return $output;
	}
}