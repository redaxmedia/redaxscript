<?php
namespace Redaxscript\Validator;

use Redaxscript\Hash;

/**
 * children class to validate password
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class Password implements ValidatorInterface
{
	/**
	 * validate the password
	 *
	 * @since 4.0.0
	 *
	 * @param string $password plain password
	 * @param string $hash hashed password
	 *
	 * @return bool
	 */

	public function validate(string $password = null, string $hash = null) : bool
	{
		$passwordHash = new Hash();
		return $password && $passwordHash->validate($password, $hash);
	}
}