<?php
//TODO: Please provide a better description; usual parent class to ... or children class to do something
/**
 * Login validator
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Redaxscript_Validator
 * @author Sven Weingartner
 */

class Redaxscript_Validator_Login implements Redaxscript_Validator_Interface
{
	/**
	 * minimal length for login names
	 */
	const LENGTH_MIN = 5;

	/**
	 * maximal length for login names
	 */
	const LENGTH_MAX = 52;

	/**
	 * check if the login name is valid.
	 * Current rule set:
	 * - min 5 chars
	 * - max 52 chars
	 * - only numbers and chars are allowed, no special chars
	 *
	 * @since 2.2.0
	 *
	 * @param string $login The login name
	 *
	 * @return integer
	 */

	public function validate($login = null)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		if (!empty($login) && ctype_alnum($login) && strlen($login) >= self::LENGTH_MIN && strlen($login) <= self::LENGTH_MAX)
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}
		return $output;
	}
}