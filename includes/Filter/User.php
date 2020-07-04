<?php
namespace Redaxscript\Filter;

use function preg_replace;
use function trim;

/**
 * children class to filter the user
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class User implements FilterInterface
{
	/**
	 * pattern for user
	 *
	 * @var string
	 */

	protected $_pattern = '[^a-zA-Z0-9\._-]';

	/**
	 * sanitize the user
	 *
	 * @since 4.3.0
	 *
	 * @param string $user user to be sanitized
	 *
	 * @return string|null
	 */

	public function sanitize(string $user = null) : ?string
	{
		return trim(preg_replace('/' . $this->_pattern . '/i', null, $user)) ? : null;
	}
}
