<?php
namespace Redaxscript\Filter;

use function ltrim;
use function preg_replace;
use function str_replace;
use function trim;
use function urldecode;

/**
 * children class to filter the path
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Path implements FilterInterface
{
	/**
	 * sanitize the path
	 *
	 * @since 2.6.0
	 *
	 * @param string $path name of the path
	 * @param string $separator directory separator
	 *
	 * @return string
	 */

	public function sanitize(string $path = null, string $separator = DIRECTORY_SEPARATOR) : string
	{
		$output = urldecode($path);
		$output = str_replace(
		[
			' ',
			'..',
		], null, $output);
		$output = preg_replace('~' . $separator . '+~', $separator, $output);
		$output = ltrim($output, '.');
		$output = trim($output, $separator);
		return $output;
	}
}