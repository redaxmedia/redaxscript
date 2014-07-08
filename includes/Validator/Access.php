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
class Redaxscript_Validator_Access implements Redaxscript_Validator_Interface
{

	/**
	 * @var array|int
	 */

	private $_access;

	/**
	 * @var array
	 */

	private $_groups;

	/**
	 * check dns
	 *
	 * @since 2.2.0
	 *
	 * @param array|int $access
	 * @param array $groups
	 */

	public function __construct($access = '', array $groups = array())
	{
		$this->_access = $access;
		$this->_groups = $groups;
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
		$access_array = explode(', ', $this->_access);
		$groups_array = explode(', ', $this->_groups);

		/* intersect access and groups */

		if ($this->_access == 0 || in_array(1, $groups_array) || array_intersect($access_array, $groups_array))
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_OK;
		}
		else
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
		}

		return $output;
	}
}
