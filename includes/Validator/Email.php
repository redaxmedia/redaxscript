<?php
namespace Redaxscript\Validator;

/**
 * children class to validate email
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Email implements Validator
{
	/**
	 * validate the email
	 *
	 * @since 2.2.0
	 *
	 * @param string $email target email address
	 * @param boolean $dns optional validate dns
	 *
	 * @return integer
	 */

	public function validate($email = null, $dns = true)
	{
		$output = Validator::FAILED;

		/* validate email */

		if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
		{
			$output = Validator::PASSED;
			$emailArray = array_filter(explode('@', $email));

			/* validate dns */

			if ($dns === true)
			{
				$dnsValidator = new Dns();
				$output = $dnsValidator->validate($emailArray[1], 'MX');
			}
		}
		return $output;
	}
}