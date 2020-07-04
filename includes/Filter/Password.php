<?php
namespace Redaxscript\Filter;

use function preg_replace;

/**
 * children class to filter the password
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Password implements FilterInterface
{
	/**
	 * pattern for password
	 *
	 * @var string
	 */

	protected $_pattern = '[^\S]';

	/**
	 * sanitize the password
	 *
	 * @since 4.3.0
	 *
	 * @param string $password plain password
	 *
	 * @return string|null
	 */

	public function sanitize(string $password = null) : ?string
	{
		return preg_replace('/' . $this->_pattern . '/i', null, $password) ? : null;
	}
}
