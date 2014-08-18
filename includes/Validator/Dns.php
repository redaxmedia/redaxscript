<?php

/**
 * DNS validator
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Redaxscript_Validator
 * @author Sven Weingartner
 */

class Redaxscript_Validator_Dns implements Redaxscript_Validator_Interface
{
	/**
	 * check if domain is a valid domain
	 */
	const DNS_TYPE_A = 'A';

	/**
	 * check if domain is registered as a mail server
	 */
	const DNS_TYPE_MX = 'MX';


	/**
	 * validates DNS records corresponding to a given internet host name or IP address
	 *
	 * @since 2.2.0
	 *
	 * @param string $host host or ip of the domain
	 * @param string $type optional type
	 *
	 * @return integer
	 */

	public function validate($host = '', $type = self::DNS_TYPE_MX)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		if ($host)
		{
			if (function_exists('checkdnsrr') && checkdnsrr($host, $type) === false)
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
			}
			else
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
		}
		return $output;
	}
}