<?php

/**
 * children class to validate email
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Redaxscript_Validator
 * @author Sven Weingartner
 */

class Redaxscript_Validator_Email implements Redaxscript_Validator_Interface
{
	/**
	 * validate the email
	 *
	 * @since 2.2.0
	 *
	 * @param string $email email address
	 * @param string $dns optional dns validation
	 *
	 * @return integer
	 */

	public function validate($email = null, $dns = true)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		/* validate email */

		if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			$emailArray = explode('@', $email);

			/* validate domain */

			if ($dns === true)
			{
				$dnsValidator = new Redaxscript_Validator_Dns();
				$output = $dnsValidator->validate($emailArray[1], Redaxscript_Validator_Dns::DNS_TYPE_MX);
			}
		}
		return $output;
	}
}