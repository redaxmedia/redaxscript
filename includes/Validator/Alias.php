<?php

/**
 * DNS validator
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
	 * @var int
	 */

	private $_alias;

	/**
	 * @var int
	 */

	private $_mode;

	/**
	 * check dns
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias
	 * @param string $mode
	 */

	public function __construct($alias = '', $mode = '')
	{
		$this->_alias = $alias;
		$this->_mode = $mode;
	}

	/**
	 * checks the validator
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 *
	 * @return integer
	 */

	public function validate()
	{
		/* validate alias */

		if ($this->_mode == self::ALIAS_MODE_USER)
		{
			if ($this->_alias != clean_alias($this->_alias) || is_numeric($this->_alias))
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
			else
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
			}
		}

		/* check for default alias */

		else if ($this->_mode == self::ALIAS_MODE_DEFAULT)
		{
			if (in_array($this->_alias, self::$defaultAliases))
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
			else
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
			}
		}

		return $output;
	}
}
