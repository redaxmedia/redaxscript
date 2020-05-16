<?php
namespace Redaxscript\Validator;

use function in_array;
use function preg_match;

/**
 * children class to validate general and default alias
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Alias implements ValidatorInterface
{
	/**
	 * pattern for alias
	 *
	 * @var string
	 */

	protected $_pattern = '^[a-zA-Z0-9-]{3,100}$';

	/**
	 * array of system alias
	 *
	 * @var array
	 */

	protected $_systemArray =
	[
		'admin',
		'login',
		'recover',
		'reset',
		'logout',
		'register',
		'module',
		'search'
	];

	/**
	 * get the pattern
	 *
	 * @since 4.3.0
	 *
	 * @return string
	 */

	public function getPattern() : string
	{
		return $this->_pattern;
	}

	/**
	 * validate the alias
	 *
	 * @since 4.3.0
	 *
	 * @param string $alias alias to be validated
	 *
	 * @return bool
	 */

	public function validate(string $alias = null) : bool
	{
		return preg_match('/' . $this->_pattern . '/', $alias);
	}

	/**
	 * match system alias
	 *
	 * @since 4.3.0
	 *
	 * @param string $alias alias to be matched
	 *
	 * @return bool
	 */

	public function matchSystem(string $alias = null) : bool
	{
		return in_array($alias, $this->_systemArray);
	}
}
