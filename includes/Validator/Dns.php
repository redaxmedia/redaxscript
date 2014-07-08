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
class Redaxscript_Validator_Dns implements Redaxscript_Validator_Interface
{

	/**
	 * @var string
	 */

	private $_input;

	/**
	 * @var string
	 */

	private $_type;

	/**
	 * check dns
	 *
	 * @since 2.2.0
	 *
	 * @param string $input
	 * @param string $type
	 */

	public function __construct($input = '', $type = '')
	{
		$this->_input = $input;
		$this->_type = $type;
	}

	/**
	 * checkes the validator
	 *
	 * @since 2.2.0
	 *
	 * @author Henry Ruhs
	 *
	 * @return integer
	 */

	public function validate()
	{
		if ($this->_input)
		{
			if (function_exists('checkdnsrr') && checkdnsrr($this->_input, $this->_type) == '')
			{
				$output = 0;
			}
			else
			{
				$output = 1;
			}
		}
		else
		{
			$output = 0;
		}

		return $output;
	}
}
