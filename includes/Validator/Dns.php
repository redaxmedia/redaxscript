<?php
namespace Redaxscript\Validator;

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

class Dns implements Validator
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
		$output = Validator::FAILED;

		/* validate dns */

		if ($host)
		{
			if (function_exists('checkdnsrr') && checkdnsrr($host, $type) === false)
			{
				$output = Validator::FAILED;
			}
			else
			{
				$output = Validator::PASSED;
			}
		}
		return $output;
	}
}