<?php

/**
 * children class to validate captcha
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Redaxscript_Validator
 * @author Sven Weingartner
 */

class Redaxscript_Validator_Captcha implements Redaxscript_Validator_Interface
{
	/**
	 * validate the captcha
	 *
	 * @since 2.2.0
	 *
	 * @param string $raw plain answer
	 * @param string $hash hashed solution
	 *
	 * @return integer
	 */

	public function validate($raw = null, $hash = null)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		/* validate raw and hash */

		if (sha1($raw) === $hash || Redaxscript\Db::getSettings('captcha') == 0)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}
		return $output;
	}
}