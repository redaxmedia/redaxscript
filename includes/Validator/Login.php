<?php
namespace Redaxscript\Validator;

/**
 * children class to validate login
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Login implements Validator
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
	 * validate the url
	 *
	 * @since 2.2.0
	 *
	 * @param string $url target url
	 * @param string $dns optional validate dns
	 *
	 * @return integer
	 */

	public function validate($login = null)
	{
		$output = Validator::FAILED;
		$length = strlen($login);

		/* validate login */

		if (ctype_alnum($login) && $length >= $this->_range['min'] && $length <= $this->_range['max'])
		{
			$output = Validator::PASSED;
		}
		return $output;
	}
}