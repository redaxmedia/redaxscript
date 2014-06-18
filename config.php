<?php

/**
 * parent class to store database config
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Config
 * @author Henry Ruhs
 */

class Redaxscript_Config
{
	/**
	 * database config
	 *
	 * @var array
	 */

	private $_config = array(
		// [config]
		'host' => '',
		'name' => '',
		'user' => '',
		'password' => '',
		'prefix' => '',
		'salt' => ''
		// [/config]
	);

	/**
	 * get item from config
	 *
	 * @since 2.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array
	 */

	public function get($key = null)
	{
		$output = null;

		/* values as needed */

		if (is_null($key))
		{
			$output = $this->_config;
		}
		else if (array_key_exists($key, $this->_config))
		{
			$output = $this->_config[$key];
		}
		return $output;
	}
}
