<?php
namespace Redaxscript\Validator;

use function filter_var;
use function parse_url;

/**
 * children class to validate url
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Url implements ValidatorInterface
{
	/**
	 * validate the url
	 *
	 * @since 4.0.0
	 *
	 * @param string $url url address
	 * @param bool $dns optional validate dns
	 *
	 * @return bool
	 */

	public function validate(string $url = null, bool $dns = true) : bool
	{
		if (filter_var($url, FILTER_VALIDATE_URL))
		{
			$dnsValidator = new Dns();
			$urlArray = parse_url($url);
			return $dns ? $dnsValidator->validate($urlArray['host']) : true;
		}
		return false;
	}
}
