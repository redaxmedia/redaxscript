<?php
namespace Redaxscript\Validator;

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

interface Validator
{
	/**
	 * status passed
	 *
	 * @const integer
	 */

	const PASSED = 1;

	/**
	 * status failed
	 *
	 * @const integer
	 */

	const FAILED = 0;

	/**
	 * validate the value
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $value general value to validate
	 *
	 * @return integer
	 */

	public function validate($value = null);
}
