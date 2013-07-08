<?php

/**
 * Redaxscript Config
 *
 * @since 2.0
 *
 * @package Redaxscript
 * @category Config
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
	 * @param string $key
	 * @return string
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