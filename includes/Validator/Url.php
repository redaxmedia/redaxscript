<?php
namespace Redaxscript\Validator;
use Redaxscript\Validator;
use Redaxscript_Validator_Interface;

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

class Url implements Redaxscript_Validator_Interface
{
	/**
	 * validate the url
	 *
	 * @since 2.2.0
	 *
	 * @param string $url target url
	 * @param string $dns optional validate dns
	 *
	 * @return integer
	 */

	public function validate($url = null, $dns = true)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		/* validate url */

		if (filter_var($url, FILTER_VALIDATE_URL) !== false)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;

			/* validate dns */

			if ($dns === true)
			{
				$parsedUrl = parse_url($url);
				if (isset($parsedUrl['host']))
				{
					$dnsValidator = new Validator\Dns();
					$output = $dnsValidator->validate($parsedUrl['host']);
				}
				else
				{
					$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
				}
			}
		}
		return $output;
	}
}
