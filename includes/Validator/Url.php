<?php

/**
 * URL validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Url implements Redaxscript_Validator_Interface
{

	/**
	 * check url
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 * @author Sven Weingartner
	 *
	 * @param string $input
	 *
	 * @return integer
	 */

	function validate($input = '')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		if ($input == clean_url($input)
			&& filter_var($input, FILTER_VALIDATE_URL) !== false
		)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}

		return $output;
	}
}