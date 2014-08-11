<?php

/**
 * children class to validate url
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Redaxscript_Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Url implements Redaxscript_Validator_Interface
{
	/**
	 * validate the url
	 *
	 * @since 2.2.0
	 *
	 * @param string $url universal resource locator
	 * @param string $dns optional dns validation
	 *
	 * @return integer
	 */

	public function validate($url = null, $dns = true)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		/* validate url */

		if (filter_var($url, FILTER_VALIDATE_URL) !== false)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}

		/* validate domain */

		if ($dns === true)
		{
			$parsedUrl = parse_url($url);
			if (isset($parsedUrl['host']))
			{
				$dnsValidator = new Redaxscript_Validator_Dns();
				$output = $dnsValidator->validate($parsedUrl['host'], Redaxscript_Validator_Dns::DNS_TYPE_A);
			}
			else
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
			}
		}

		return $output;
	}
}
