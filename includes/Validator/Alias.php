<?php
namespace Redaxscript\Validator;

use function in_array;
use function preg_match;
use function strlen;

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
	 * pattern for login
	 *
	 * @var string
	 */

	protected $_pattern = '[a-z0-9-]';

	/**
	 * allowed range for alias
	 *
	 * @var array
	 */

	protected $_rangeArray =
	[
		'min' => 3,
		'max' => 100
	];

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
	 * validate the alias
	 *
	 * @since 4.0.0
	 *
	 * @param string $alias alias for routes and users
	 * @param string $mode general or default validation
	 *
	 * @return bool
	 */

	public function validate(string $alias = null, string $mode = 'general') : bool
	{
		if ($mode === 'general')
		{
			$length = strlen($alias);
			return preg_match('/' . $this->_pattern . '/i', $alias) && $length >= $this->_rangeArray['min'] && $length <= $this->_rangeArray['max'];
		}
		if ($mode === 'system')
		{
			return !in_array($alias, $this->_systemArray);
		}
		return false;
	}
}
