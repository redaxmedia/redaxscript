<?php
//TODO: Please provide a better description; usual parent class to ... or children class to do something
/**
 * Login validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
//TODO: @category before @package please - I change this in the other classes
//TODO: Missing whitespace
class Redaxscript_Validator_Login implements Redaxscript_Validator_Interface
{
//TODO: No introductional whitespace here
//TODO: Please use constant block like @vars
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
//TODO: docblock 1. better description
//TODO: docblock 2. no author inside method blocks
//TODO: docblock 3. missing variable description
	public function validate($input = '') //TODO: 1. please provide better naming, in this case $login 2. use null over ''
	{
		$validator = new Redaxscript_Validator_String();
		$output = $validator->validate($input, self::LENGTH_MIN, self::LENGTH_MAX);
		//TODO: please remove whitespace before outputs
		return $output;
	}
}