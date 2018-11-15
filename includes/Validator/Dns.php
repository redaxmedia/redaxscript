<?php
namespace Redaxscript\Validator;

use function checkdnsrr;
use function function_exists;

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
	 * @since 4.0.0
	 *
	 * @param string $host host of the domain
	 * @param string $type optional domain type
	 *
	 * @return bool
	 */

	public function validate(string $host = null, string $type = 'a') : bool
	{
		return function_exists('checkdnsrr') ? checkdnsrr($host, $type) : true;
	}
}