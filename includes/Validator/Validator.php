<?php
namespace Redaxscript\Validator;

/**
 * interface to define a validator class
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

interface Validator
{
	/**
	 * status passed
	 *
	 * @const boolean
	 */

	const PASSED = true;

	/**
	 * status failed
	 *
	 * @const boolean
	 */

	const FAILED = false;

	/**
	 * validate the value
	 *
	 * @since 2.2.0
	 *
	 * @param string $value general value to validate
	 *
	 * @return boolean
	 */

	public function validate($value = null);
}
