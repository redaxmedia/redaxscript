<?php
namespace Redaxscript\Validator;

use function preg_match;

/**
 * children class to validate name
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class Name implements ValidatorInterface
{
	/**
	 * pattern for name
	 *
	 * @var string
	 */

	protected $_pattern = '^(([a-zA-Z0-9]\s)*([a-zA-Z0-9])){3,100}$';

	/**
	 * get the pattern
	 *
	 * @since 4.3.0
	 *
	 * @return string
	 */

	public function getPattern() : string
	{
		return $this->_pattern;
	}

	/**
	 * validate the user
	 *
	 * @since 4.3.0
	 *
	 * @param string $name name to be validated
	 *
	 * @return bool
	 */

	public function validate(string $name = null) : bool
	{
		return preg_match('/' . $this->_pattern . '/', $name);
	}
}
