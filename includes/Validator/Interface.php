<?php

/**
 * interface for a validator
 *
 * @since 2.2.0
 *
 * @category Validator
 * @package Redaxscript
 * @author Sven Weingartner
 */

interface Redaxscript_Validator_Interface
{

	const VALIDATION_OK = 1;
	const VALIDATION_FAIL = 0;
	//TODO: maby we use true and false instead
	//TODO: integer would make sence if you have _WARNING for value 2

	/**
	 * general validate function
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $value general value to validate
	 *
	 * @return integer
	 */

	public function validate($value = null);
}
