<?php
namespace Redaxscript\Validator;

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
			return is_numeric($alias) || preg_match('/[^a-z0-9-]/i', $alias);
		}
		if ($mode === 'system')
		{
			return in_array($alias, $this->_systemArray);
		}
		return false;
	}
}
