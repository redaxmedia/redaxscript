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
	 * @param string $url url address
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

			if ($dns)
			{
				$urlArray = parse_url($url);
				if (is_array($urlArray) && array_key_exists('host', $urlArray))
				{
					$dnsValidator = new Dns();
					$output = $dnsValidator->validate($urlArray['host']);
				}
			}
		}
		return $output;
	}
}
