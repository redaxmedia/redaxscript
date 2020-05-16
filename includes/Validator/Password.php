<?php
namespace Redaxscript\Validator;

use Redaxscript\Hash;
use function preg_match;

/**
 * children class to validate password
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class Password implements ValidatorInterface
{
	/**
	 * pattern for password
	 *
	 * @var string
	 */

	protected $_pattern = '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])\S{10,100}$';

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
	 * validate the password
	 *
	 * @since 4.3.0
	 *
	 * @param string $password password to be validated
	 *
	 * @return bool
	 */

	public function validate(string $password = null) : bool
	{
		return preg_match('/' . $this->_pattern . '/', $password);
	}

	/**
	 * match password hash
	 *
	 * @since 4.3.0
	 *
	 * @param string $password password to be validated
	 * @param string $hash hash to be validated
	 *
	 * @return bool
	 */

	public function matchHash(string $password = null, string $hash = null) : bool
	{
		$passwordHash = new Hash();
		return $password && $passwordHash->validate($password, $hash);
	}
}
