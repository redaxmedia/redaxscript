<?php
namespace Redaxscript\Validator;

/**
 * children class to validate login
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Login implements ValidatorInterface
{
	/**
	 * allowed range for login
	 *
	 * @var array
	 */

	protected $_range = array(
		'min' => 5,
		'max' => 50
	);

	/**
	 * validate the login
	 *
	 * @since 2.2.0
	 *
	 * @param string $login login name
	 *
	 * @return integer
	 */

	public function validate($login = null)
	{
		$output = ValidatorInterface::FAILED;
		$length = strlen($login);

		/* validate login */

		if (ctype_alnum($login) && $length >= $this->_range['min'] && $length <= $this->_range['max'])
		{
			$output = ValidatorInterface::PASSED;
		}
		return $output;
	}
}