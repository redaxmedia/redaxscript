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
	 * filter the value
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $value general value to filter
	 *
	 * @return string
	 */

	public function filter($value = null);
}
