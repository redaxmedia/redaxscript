<?php
namespace Redaxscript\Validator;

use function ctype_alnum;
use function strlen;

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

	protected $_rangeArray =
	[
		'min' => 1,
		'max' => 30
	];

	/**
	 * validate the login
	 *
	 * @since 4.0.0
	 *
	 * @param string $login login
	 *
	 * @return bool
	 */

	public function validate(string $login = null) : bool
	{
		$length = strlen($login);
		return ctype_alnum($login) && $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'];
	}
}