<?php
namespace Redaxscript\Validator;

use Redaxscript\Hash;

/**
 * children class to validate captcha
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class Captcha implements ValidatorInterface
{
	/**
	 * validate the captcha
	 *
	 * @since 4.0.0
	 *
	 * @param int $task plain task
	 * @param string $hash hashed solution
	 *
	 * @return bool
	 */

	public function validate(int $task = null, string $hash = null) : bool
	{
		$captchaHash = new Hash();
		return $captchaHash->validate($task, $hash);
	}
}
