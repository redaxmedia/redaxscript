<?php
namespace Redaxscript\Validator;

use function preg_match;
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
	 * pattern for login
	 *
	 * @var string
	 */

	protected $_pattern = '[a-zA-Z0-9-]';

	/**
	 * allowed range for login
	 *
	 * @var array
	 */

	protected $_rangeArray =
	[
		'min' => 3,
		'max' => 50
	];

	/**
	 * get the form pattern
	 *
	 * @since 4.1.0
	 *
	 * @return string
	 */

	public function getFormPattern() : string
	{
		return $this->_pattern . '{' . $this->_rangeArray['min'] . ',' . $this->_rangeArray['max']  . '}';
	}

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
		return preg_match('/' . $this->_pattern . '/i', $login) && $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'];
	}
}