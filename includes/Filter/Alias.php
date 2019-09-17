<?php
namespace Redaxscript\Filter;

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
		'search' =>
		[
			'[^a-zA-Z0-9]',
			'\s+'
		],
		'replace' =>
		[
			' ',
			'-'
		]
	];

	/**
	 * sanitize the alias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias content and user alias
	 *
	 * @return string
	 */

	public function sanitize(string $alias = null) : string
	{
		$output = preg_replace('/' . $this->_patternArray['search'][0] . '/i', $this->_patternArray['replace'][0], $alias);
		$output = preg_replace('/' . $this->_patternArray['search'][1] . '/i', $this->_patternArray['replace'][1], trim($output));
		return $output;
	}
}