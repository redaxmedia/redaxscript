<?php
namespace Redaxscript\Filter;

/**
 * children class to filter the search
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Search implements FilterInterface
{
	/**
	 * sanitize the search
	 *
	 * @since 3.0.0
	 *
	 * @param string $search search term
	 *
	 * @return string
	 */

	public function sanitize($search = null)
	{
		return preg_replace('/[^a-zA-Z0-9-]/i', null, $search);
	}
}
