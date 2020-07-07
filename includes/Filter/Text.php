<?php
namespace Redaxscript\Filter;

use function filter_var;
use function trim;

/**
 * children class to filter the text
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Text implements FilterInterface
{
	/**
	 * sanitize the text
	 *
	 * @since 4.3.0
	 *
	 * @param int|string $text text to be sanitized
	 *
	 * @return string
	 */

	public function sanitize($text = null) : ?string
	{
		return (string)trim(filter_var($text, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK)) ? : null;
	}
}
