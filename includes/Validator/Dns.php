<?php
namespace Redaxscript\Validator;

/**
 * children class to validate domain name service
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Dns implements ValidatorInterface
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
		$output = ValidatorInterface::FAILED;

		/* validate dns */

		if ($host)
		{
			if (function_exists('checkdnsrr') && checkdnsrr($host, $type) === false)
			{
				$output = ValidatorInterface::FAILED;
			}
			else
			{
				$output = ValidatorInterface::PASSED;
			}
		}
		return $output;
	}
}