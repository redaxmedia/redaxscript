<?php
namespace Redaxscript\Validator;

/**
 * children class to validate url
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Url implements Validator
{
	/**
	 * validate the url
	 *
	 * @since 2.2.0
	 *
	 * @param string $url target url address
	 * @param boolean $dns optional validate dns
	 *
	 * @return integer
	 */

	public function validate($url = null, $dns = true)
	{
		$output = Validator::FAILED;

		/* validate url */

		if (filter_var($url, FILTER_VALIDATE_URL) !== false)
		{
			$output = Validator::PASSED;

			/* validate dns */

			if ($dns === true)
			{
				$parsedUrl = parse_url($url);
				if (isset($parsedUrl['host']))
				{
					$dnsValidator = new Dns();
					$output = $dnsValidator->validate($parsedUrl['host']);
				}
			}
		}
		return $output;
	}
}
