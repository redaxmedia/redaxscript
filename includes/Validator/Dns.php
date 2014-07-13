<?php

/**
 * DNS validator
 *
 * @since 2.2.0
 *
 * @category Validator
 * @package Redaxscript
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
	 * @param string $host host of the domain
	 * @param string $type optional type
	 *
	 * @return integer
	 */

	public function validate($host = '', $type = '')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
//TODO: what is happening here, more documentation please
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
//TODO: What about renaming to Validator_Domain ?