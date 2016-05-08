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
	 * @param Request $request instance of the registry class
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

	public function init ()
	{
		$this->_parseArguments($this->_request->getServer('argv'));
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
	 * parse raw arguments
	 *
	 * @since 3.0.0
	 *
	 * @param array $arguments raw arguments to be parsed
	 */

	protected function _parseArguments($arguments = array())
	{
		$argumentKey = 0;
		foreach($arguments as $value)
		{
			if(substr($value, 0, 2) === '--')
			{
				$this->_parseOptions($value, 'long');
			}
			else if(substr($value, 0, 1) === '-')
			{
				$this->_parseOptions($value, 'short');
			}
			else if ($value)
			{
				$this->setArgument($argumentKey++, $value);
			}
		}
	}

	/**
	 * parse raw options
	 *
	 * @since 3.0.0
	 *
	 * @param string $value value of the options
	 * @param string $mode mode of the options
	 */

	protected function _parseOptions($value = null, $mode = 'short')
	{
		$modePosition = $mode === 'short' ? 1 : 2;
		$equalPosition = strpos($value, '=') ? strpos($value, '=') : strpos($value, ' ');
		if ($equalPosition)
		{
			$optionKey = substr($value, $modePosition, $equalPosition - $modePosition);
			$optionValue = substr($value, $equalPosition + 1);
		}
		else
		{
			$optionKey = substr($value, $modePosition);
			$optionValue = true;
		}
		$this->setOption($optionKey, $optionValue);
	}
}
