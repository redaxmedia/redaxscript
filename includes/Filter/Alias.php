<?php
namespace Redaxscript\Filter;

use function iconv;
use function preg_replace;
use function trim;

/**
 * children class to filter the alias
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Alias implements FilterInterface
{
	/**
	 * pattern for alias
	 *
	 * @var array
	 */

	protected $_patternArray =
	[
		'search' => '[^a-zA-Z0-9]+',
		'replace' => '-'
	];

	/**
	 * sanitize the alias
	 *
	 * @since 4.3.0
	 *
	 * @param string $alias alias to be sanitized
	 *
	 * @return string
	 */

	public function sanitize(string $alias = null) : ?string
	{
		$output = iconv(null, 'ascii//translit', trim($alias));
		return preg_replace('/' . $this->_patternArray['search'] . '/', $this->_patternArray['replace'], $output) ? : null;
	}
}
