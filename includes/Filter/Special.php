<?php
namespace Redaxscript\Filter;

use function preg_replace;

/**
 * children class to filter the special
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Special implements FilterInterface
{
	/**
	 * pattern for special
	 *
	 * @var string
	 */

	protected $_pattern = '[^a-zA-Z0-9]';

	/**
	 * sanitize the special
	 *
	 * @since 3.0.0
	 *
	 * @param string $special special character
	 *
	 * @return string
	 */

	public function sanitize(string $special = null) : string
	{
		return preg_replace('/' . $this->_pattern . '/i', null, $special);
	}
}
