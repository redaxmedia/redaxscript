<?php
namespace Redaxscript\Filter;

use function array_filter;
use function array_map;
use function explode;
use function implode;

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
	 * @since 4.3.0
	 *
	 * @param string $path path to be sanitized
	 * @param string $separator directory separator
	 *
	 * @return string
	 */

	public function sanitize(string $path = null, string $separator = DIRECTORY_SEPARATOR) : string
	{
		$pathArray = explode($separator, $path);
		foreach ($pathArray as $key => $value)
		{
			if ($value === '.' || $value === '..')
			{
				$pathArray[$key] = null;
			}
		}
		return implode($separator, array_map('trim', array_filter($pathArray)));
	}
}
