<?php

/**
 * DNS validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Dns implements Redaxscript_Validator_Interface
{

	const DNS_TYPE_A = 'A';
	const DNS_TYPE_MX = 'MX';


	/**
	 * checks the validator
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 * @author Sven Weingartner
	 *
	 * @param string $host host may either be the IP address in dotted-quad notation or the host name
	 * @param string $type type may be any one of: A, MX, NS, SOA, PTR, CNAME, AAAA, A6, SRV, NAPTR, TXT or ANY
	 *
	 * @return integer
	 */

	public function validate($host = '', $type = '')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		if ($host)
		{
			// Note: The checkdnsrr function is not implemented on the Windows platform
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
