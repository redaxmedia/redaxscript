<?php
namespace Redaxscript\Validator;

use Redaxscript\Config;
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
	 * @since 2.6.0
	 *
	 * @param string $password plain password
	 * @param string $hash hashed password
	 *
	 * @return boolean
	 */

	public function validate($password = null, $hash = null)
	{
		$output = ValidatorInterface::FAILED;
		$passwordHash = new Hash(Config::getInstance());

		/* validate password */

		if ($password && $passwordHash->validate($password, $hash))
		{
			$output = ValidatorInterface::PASSED;
		}
		return $output;
	}
}