<?php
namespace Redaxscript\Validator;
use Redaxscript_Validator_Interface;

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

class Login implements Redaxscript_Validator_Interface
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
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
		$length = strlen($login);

		/* validate login */

		if (ctype_alnum($login) && $length >= $this->_range['min'] && $length <= $this->_range['max'])
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}
		return $output;
	}
}