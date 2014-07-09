<?php

/**
 * Alias validator
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Alias implements Redaxscript_Validator_Interface
{

	const ALIAS_MODE_USER = 0;
	const ALIAS_MODE_DEFAULT = 1;

	const ALIAS_DEFAULT_ADMIN = 'admin';
	const ALIAS_DEFAULT_LOADER = 'loader';
	const ALIAS_DEFAULT_LOGIN = 'login';
	const ALIAS_DEFAULT_LOGOUT = 'logout';
	const ALIAS_DEFAULT_PASSWORD_RESET = 'password_reset';
	const ALIAS_DEFAULT_SCRIPTS = 'scripts';
	const ALIAS_DEFAULT_STYLES = 'styles';
	const ALIAS_DEFAULT_REGISTRATION = 'registration';
	const ALIAS_DEFAULT_REMINDER = 'reminder';


	public static $defaultAliases = array(
		self::ALIAS_DEFAULT_ADMIN,
		self::ALIAS_DEFAULT_LOADER,
		self::ALIAS_DEFAULT_LOGIN,
		self::ALIAS_DEFAULT_LOGOUT,
		self::ALIAS_DEFAULT_PASSWORD_RESET,
		self::ALIAS_DEFAULT_SCRIPTS,
		self::ALIAS_DEFAULT_STYLES,
		self::ALIAS_DEFAULT_REGISTRATION,
		self::ALIAS_DEFAULT_REMINDER
	);


	/**
	 * checks the validator
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 * @author Sven Weingartner
	 *
	 * @param string $alias
	 * @param string $mode
	 *
	 * @return integer
	 */

	public function validate($alias = '', $mode = '')
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		/* validate alias */

		if ($mode == self::ALIAS_MODE_USER)
		{
			if ($alias != clean_alias($alias) || is_numeric($alias))
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
		}

		/* check for default alias */

		else if ($mode == self::ALIAS_MODE_DEFAULT)
		{
			if (in_array($alias, self::$defaultAliases))
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
		}

		return $output;
	}
}
