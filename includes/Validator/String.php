<?php

/**
 * String validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_String implements Redaxscript_Validator_Interface
{

	/**
	 * check string
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

	public function validate($input = '', $minLength = 0, $maxLength = 999)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		if (ctype_alnum($input)
			&& strlen($input) >= $minLength
			&& strlen($input) <= $maxLength
		)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}

		return $output;
	}
}