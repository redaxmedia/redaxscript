<?php
namespace Redaxscript\Validator;

/**
 * interface to define a validator class
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

interface ValidatorInterface
{
	/**
	 * validate the value
	 *
	 * @since 2.2.0
	 */

	public function validate();
}
