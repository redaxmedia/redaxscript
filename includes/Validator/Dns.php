<?php
namespace Redaxscript\Validator;
use Redaxscript_Validator_Interface;

/**
 * children class to validate domain name service
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Dns implements Redaxscript_Validator_Interface
{
	/**
	 * validate the dns
	 *
	 * @since 2.2.0
	 *
	 * @param string $host host of the domain
	 * @param string $type optional domain type
	 *
	 * @return integer
	 */

	public function validate($host = null, $type = 'A')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		/* validate dns */

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