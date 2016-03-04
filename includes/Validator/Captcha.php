<?php
namespace Redaxscript\Validator;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Hash;

/**
 * children class to validate captcha
 *
 * @since 3.0.0
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
	 * @since 3.0.0
	 *
	 * @param string $task plain task
	 * @param string $solution hashed solution
	 *
	 * @return integer
	 */

	public function validate($task = null, $solution = null)
	{
		$output = ValidatorInterface::FAILED;
		$captchaHash = new Hash(Config::getInstance());

		/* validate captcha */

		if ($task && $captchaHash->validate($task, $solution) || Db::getSettings('captcha') < 1)
		{
			$output = ValidatorInterface::PASSED;
		}
		return $output;
	}
}
