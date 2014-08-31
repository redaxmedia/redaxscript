<?php
namespace Redaxscript\Filter;

/**
 * interface to build a filter class
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Filter
 * @author Henry Ruhs
 */

interface Filter
{
	/**
	 * sanitize the value
	 *
	 * @since 2.2.0
	 *
	 * @param string $value general value to filter
	 *
	 * @return string
	 */

	public function sanitize($value = null);
}
