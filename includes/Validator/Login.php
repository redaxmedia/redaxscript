<?php

/**
 * Login validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Login implements Redaxscript_Validator_Interface
{

	const LENGTH_MIN = 5;
	const LENGTH_MAX = 52;

	/**
	 * check login
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

	public function validate($input = '')
	{
		$validator = new Redaxscript_Validator_String();
		$output = $validator->validate($input, self::LENGTH_MIN, self::LENGTH_MAX);

		return $output;
	}
}