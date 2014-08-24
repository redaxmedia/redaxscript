<?php
namespace Redaxscript\Validator;

use Redaxscript\Db;

/**
 * children class to validate captcha raw again hash
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Captcha implements Validator
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
		$output = Validator::FAILED;

		/* validate raw again hash */

		if (sha1($raw) === $hash || Db::getSettings('captcha') == 0)
		{
			$output = Validator::PASSED;
		}
		return $output;
	}
}