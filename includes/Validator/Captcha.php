<?php

/**
 * Captcha validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Captcha implements Redaxscript_Validator_Interface
{

	/**
	 * check captcha
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 * @author Sven Weingartner
	 *
	 * @param string $task
	 * @param string $solution
	 *
	 * @return integer
	 */

	public function validate($task = '', $solution = '')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		if (sha1($task) == $solution || s('captcha') == 0)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}

		return $output;
	}
}