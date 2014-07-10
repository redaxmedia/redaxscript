<?php

/**
 * Email validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Email implements Redaxscript_Validator_Interface
{


	/**
	 * check email
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 * @author Sven Weingartner
	 *
	 * @param string $input
	 *
	 * @return integer
	 */

	public function validate($input = '')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		if (filter_var($input, FILTER_VALIDATE_EMAIL) !== false)
		{
			$inputArray = explode('@', $input);

			/* lookup domain name */

			$dnsValidator = new Redaxscript_Validator_Dns();
			$output = $dnsValidator->validate($inputArray[1], Redaxscript_Validator_Dns::DNS_TYPE_MX);
		}

		return $output;
	}
}