<?php

/**
 * constants
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Constants
 * @author Gary Aylward
 */

class Redaxscript_Constants implements ArrayAccess
{

	protected $_values = array();

	/**
	 * construct
	 *
	 * @since 2.1.0
	 *
	 * @param array $constants
	 */

	public function __construct($constants)
	{
		$this->_values = $constants;
	}

	/**
	 * init
	 *
	 * @since 2.1.0
	 *
	 * @param array $constants
	 */
	public function init($constants)
	{
		$this->_values = array();
		$this->_values = $constants;
	}


	/**
	 * offsetExists
	 *
	 * @since 2.1.0
	 *
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return array_key_exists($offset, $this->_values);
	}

	/**
	 * offsetGet
	 *
	 * @since 2.1.0
	 *
	 * @param string $offset
	 * @return string
	 */
	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? $this->_values[$offset] : null;
	}

	/**
	 * offsetSet
	 *
	 * @since 2.1.0
	 *
	 * @param string $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->_values[$offset] = $value;
	}

	/**
	 * offsetUnset
	 *
	 * @since 2.1.0
	 *
	 * @param string $offset
	 */
	public function offsetUnset($offset)
	{
		if ($this->offsetExists($offset))
		{
			unset($this->_values[$offset]);
		}
	}
}

?>