<?php

/**
 * Redaxscript Config
 *
 * @since 2.0
 *
 * @category Config
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Config
{
	/**
	 * config
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
	 * get
	 *
	 * @since 2.0
	 *
	 * @return $output string
	 */

	public function get($key = '')
	{
		if (array_key_exists($key, $this->_config))
		{
			$output = $this->_config[$key];
		}
		else
		{
			$output = '';
		}
		return $output;
	}
}
?>