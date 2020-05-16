<?php
namespace Redaxscript\Filter;

use function preg_replace;
use function trim;

/**
 * children class to filter the name
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Name implements FilterInterface
{
	/**
	 * pattern for name
	 *
	 * @var string
	 */

	protected $_pattern = '[^a-zA-Z0-9\s]';

	/**
	 * sanitize the name
	 *
	 * @since 4.3.0
	 *
	 * @param string $name name to be sanitized
	 *
	 * @return string
	 */

	public function sanitize(string $name = null) : string
	{
		return trim(preg_replace('/' . $this->_pattern . '/i', null, $name));
	}
}
