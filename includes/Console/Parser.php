<?php
namespace Redaxscript\Console;

use Redaxscript\Request;

/**
 * parent class to parse the command line interface
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Parser
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * array of parsed arguments
	 *
	 * @var array
	 */

	protected $_argumentArray = array();

	/**
	 * array of parsed options
	 *
	 * @var array
	 */

	protected $_optionArray = array();

	/**
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Request $request instance of the request class
	 */

	public function __construct(Request $request)
	{
		$this->_request = $request;
	}

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 */

	public function init()
	{
		$this->_parseArgument($this->_request->getServer('argv'));
	}

	/**
	 * get item from arguments
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return mixed
	 */

	public function getArgument($key = null)
	{
		if (array_key_exists($key, $this->_argumentArray))
		{
			return $this->_argumentArray[$key];
		}
		else if (!$key)
		{
			return $this->_argumentArray;
		}
		return false;
	}

	/**
	 * get item from options
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return mixed
	 */

	public function getOption($key = null)
	{
		if (array_key_exists($key, $this->_optionArray))
		{
			return $this->_optionArray[$key];
		}
		else if (!$key)
		{
			return $this->_optionArray;
		}
		return false;
	}

	/**
	 * set item to arguments
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setArgument($key = null, $value = null)
	{
		$this->_argumentArray[$key] = $value;
	}

	/**
	 * set item to options
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setOption($key = null, $value = null)
	{
		$this->_optionArray[$key] = $value;
	}

	/**
	 * parse raw argument
	 *
	 * @since 3.0.0
	 *
	 * @param array $argumentArray raw argument to be parsed
	 */

	protected function _parseArgument($argumentArray = array())
	{
		$argumentKey = 0;
		$i = 0;
		foreach ($argumentArray as $key => $value)
		{
			$value = $argumentArray[$i];
			$next = $argumentArray[$key + 1];
			if (substr($value, 0, 2) === '--')
			{
				$i = $this->_parseOption($value, $next, 2, $i);
			}
			else if (substr($value, 0, 1) === '-')
			{
				$i = $this->_parseOption($value, $next, 1, $i);
			}
			else if ($value)
			{
				$this->setArgument($argumentKey++, $value);
			}
			$i++;
		}
	}

	/**
	 * parse raw option
	 *
	 * @since 3.0.0
	 *
	 * @param string $option raw option to be parsed
	 * @param string $next
	 * @param integer $modeOffset offset of the mode
	 * @param integer $i
	 *
	 * @return integer
	 */
	protected function _parseOption($option = null, $next = null, $modeOffset = null, $i = null)
	{
		$equalPosition = strpos($option, '=');
		if ($equalPosition)
		{
			$optionKey = substr($option, $modeOffset, $equalPosition - $modeOffset);
			$optionValue = substr($option, $equalPosition + 1);
		}
		else
		{
			$optionKey = substr($option, $modeOffset);
			$optionValue = true;
			if ($next[0] !== '-')
			{
				$optionValue = $next;
				$i++;
			}
		}
		$this->setOption($optionKey, $optionValue);
		return $i;
	}
}
