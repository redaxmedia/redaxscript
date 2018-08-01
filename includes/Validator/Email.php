<?php
namespace Redaxscript\Validator;

/**
 * children class to validate email
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Email implements ValidatorInterface
{
	/**
	 * validate the email
	 *
	 * @since 4.0.0
	 *
	 * @param string $email email address
	 * @param bool $dns optional validate dns
	 *
	 * @return bool
	 */

	public function validate(string $email = null, bool $dns = true) : bool
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$dnsValidator = new Dns();
			$emailArray = explode('@', $email);
			return $dns ? $dnsValidator->validate($emailArray[1], 'mx') : true;
		}
		return false;
	}
}
