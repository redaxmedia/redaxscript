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

class Alias implements Filter
{
	/**
	 * filter the alias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias alias for routes and users
	 *
	 * @return string
	 */

	public function filter($alias = null)
	{
		$output = preg_replace('/\W+/', ' ', strtolower($alias));
		$output = preg_replace('/\s+/', '-', trim($output));
		return $output;
	}
}