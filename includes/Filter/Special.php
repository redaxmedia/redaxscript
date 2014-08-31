<?php
namespace Redaxscript\Filter;

/**
 * children class to filter special
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Filter
 * @author Henry Ruhs
 */

class Special implements Filter
{
	/**
	 * filter the special
	 *
	 * @since 2.2.0
	 *
	 * @param string $special target with special character
	 *
	 * @return string
	 */

	public function filter($special = null)
	{
		$output = filter_var($special, FILTER_SANITIZE_SPEC_CHARS);
		return $output;
	}
}
