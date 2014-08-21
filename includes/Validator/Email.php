<?php
namespace Redaxscript\Validator;
use Redaxscript\Validator;
use Redaxscript_Validator_Interface;

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

class Email implements Redaxscript_Validator_Interface
{
	/**
	 * validate the email
	 *
	 * @since 2.2.0
	 *
	 * @param string $email target email adress
	 * @param string $dns optional validate dns
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

			/* validate dns */

			if ($dns === true)
			{
				$dnsValidator = new Validator\Dns();
				$output = $dnsValidator->validate($emailArray[1], 'MX');
			}
		}
		return $output;
	}
}