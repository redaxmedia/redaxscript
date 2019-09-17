<?php
namespace Redaxscript\Filter;

use function preg_replace;

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
	 * pattern for search
	 *
	 * @var string
	 */

	protected $_pattern = '[^a-zA-Z0-9-]';

	/**
	 * sanitize the search
	 *
	 * @since 3.0.0
	 *
	 * @param string $search search term
	 *
	 * @return string
	 */

	public function sanitize(string $search = null) : string
	{
		return preg_replace('/' . $this->_pattern . '/i', null, $search);
	}
}
