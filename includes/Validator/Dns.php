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

	private $_host;

	/**
	 * @var string
	 */

	private $_type;

	/**
	 * check dns
	 *
	 * @since 2.2.0
	 *
	 * @param string $host host may either be the IP address in dotted-quad notation or the host name
	 * @param string $type type may be any one of: A, MX, NS, SOA, PTR, CNAME, AAAA, A6, SRV, NAPTR, TXT or ANY
	 */

	public function __construct($host = '', $type = '')
	{
		$this->_host = $host;
		$this->_type = $type;
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
		if ($this->_host)
		{
			// Note: The checkdnsrr function is not implemented on the Windows platform
			if (function_exists('checkdnsrr') && checkdnsrr($this->_host, $this->_type) === false)
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
			}
			else
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
		}
		else
		{
			$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;
		}

		return $output;
	}
}
