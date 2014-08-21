<?php

/**
 * interface to build a validator class
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

interface Redaxscript_Validator_Interface
{
	/**
	 * status passed
	 *
	 * @const integer
	 */

	const VALIDATION_OK = 1;

	/**
	 * status failed
	 *
	 * @const integer
	 */

	const VALIDATION_FAIL = 0;

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
