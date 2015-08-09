<?php
namespace Redaxscript\Filter;

/**
 * children class to filter path
 *
 * @since 2.6.0
 *
 * @category Redaxscript
 * @package Filter
 * @author Henry Ruhs
 */

class Path implements FilterInterface
{
	/**
	 * sanitize the path
	 *
	 * @since 2.6.0
	 *
	 * @param string $path target path
	 * @param string $seperator directory seperator
	 *
	 * @return string
	 */

	public function sanitize($path = null, $seperator = DIRECTORY_SEPARATOR)
	{
		$output = urldecode($path);
		$output = str_replace(array(
			' ',
			'..',
		), '', $output);
		$output = str_replace($seperator . $seperator, $seperator, $output);
		$output = ltrim($output, '.');
		$output = ltrim($output, $seperator);
		return $output;
	}
}