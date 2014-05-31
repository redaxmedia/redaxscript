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
	 * get the config
	 *
	 * @since 2.0.0
	 *
	 * @param string $key item from the config
	 *
	 * @return string
	 */

	public function get($key = null)
	{
		/* return single value */

		if (array_key_exists($key, $this->_config))
		{
			$output = $this->_config[$key];
		}

		/* else return array */

		else
		{
			$output = $this->_config;
		}
		return $output;
	}
}
?>
