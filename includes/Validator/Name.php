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

	protected $_pattern = '^(?=.{3,100}$)\S.*\S$';

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
