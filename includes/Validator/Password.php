<?php
namespace Redaxscript\Validator;

use Redaxscript\Hash;
use function preg_match;
use function strlen;

/**
 * children class to validate password
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class Password implements ValidatorInterface
{
	/**
	 * pattern for password
	 *
	 * @var string
	 */

	protected $_pattern = '(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])';

	/**
	 * allowed range for password
	 *
	 * @var array
	 */

	protected $_rangeArray =
	[
		'min' => 10,
		'max' => 100
	];

	/**
	 * get the form pattern
	 *
	 * @since 4.3.0
	 *
	 * @return string
	 */

	public function getFormPattern() : string
	{
		return $this->_pattern . '{' . $this->_rangeArray['min'] . ',' . $this->_rangeArray['max']  . '}';
	}

	/**
	 * validate the password
	 *
	 * @since 4.3.0
	 *
	 * @param string $password plain password
	 *
	 * @return bool
	 */

	public function validate(string $password = null) : bool
	{
		$length = strlen($password);
		return preg_match('/' . $this->_pattern . '/i', $password) && $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'];
	}

	/**
	 * match password hash
	 *
	 * @since 4.3.0
	 *
	 * @param string $password plain password
	 * @param string $hash hashed password
	 *
	 * @return bool
	 */

	public function matchHash(string $password = null, string $hash = null) : bool
	{
		$passwordHash = new Hash();
		return $password && $passwordHash->validate($password, $hash);
	}
}