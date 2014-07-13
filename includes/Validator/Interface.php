<?php

/**
 * interface for a validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
interface Redaxscript_Validator_Interface
{

	const VALIDATION_OK = 1;
	const VALIDATION_FAIL = 0;

	/**
	 * checks the validator
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $value
	 *
	 * @return integer
	 */

	public function validate($value);
}
