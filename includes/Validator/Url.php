<?php
namespace Redaxscript\Validator;

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
	 * @since 2.2.0
	 *
	 * @param string $url target url address
	 * @param boolean $dns optional validate dns
	 *
	 * @return boolean
	 */

	public function validate($url = null, $dns = true)
	{
		$output = ValidatorInterface::FAILED;

		/* validate url */

		if (filter_var($url, FILTER_VALIDATE_URL) !== false)
		{
			$output = ValidatorInterface::PASSED;

			/* validate dns */

			if ($dns === true)
			{
				$parsedUrl = parse_url($url);
				if (array_key_exists('host', $parsedUrl))
				{
					$dnsValidator = new Dns();
					$output = $dnsValidator->validate($parsedUrl['host']);
				}
			}
		}
		return $output;
	}
}
