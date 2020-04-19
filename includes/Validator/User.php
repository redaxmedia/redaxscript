<?php
namespace Redaxscript\Validator;

use function preg_match;
use function strlen;

/**
 * children class to validate user
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class User implements ValidatorInterface
{
	/**
	 * pattern for user
	 *
	 * @var string
	 */

	protected $_pattern = '[a-zA-Z0-9-]';

	/**
	 * allowed range for user
	 *
	 * @var array
	 */

	protected $_rangeArray =
	[
		'min' => 3,
		'max' => 100
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
	 * validate the user
	 *
	 * @since 4.0.0
	 *
	 * @param string $user user name
	 *
	 * @return bool
	 */

	public function validate(string $user = null) : bool
	{
		$length = strlen($user);
		return preg_match('/' . $this->_pattern . '/i', $user) && $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'];
	}
}