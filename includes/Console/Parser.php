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
	 * @var Request
	 */

	protected $_request;

	/**
	 * array of parsed arguments
	 *
	 * @var array
	 */

	protected $_argumentArray = [];

	/**
	 * array of parsed options
	 *
	 * @var array
	 */

	protected $_optionArray = [];

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
	 * @param string $mode name of the mode
	 *
	 * @since 3.0.0
	 */

	public function init($mode = null)
	{
		if ($mode === 'cli')
		{
			$argumentArray = $this->_request->getServer('argv');
			unset($argumentArray[0]);
		}
		else
		{
			$argumentArray = array_filter(explode(' ', $this->_request->getPost('argv')));
		}
		$this->_parseArgument($argumentArray);
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

	protected function _parseArgument($argumentArray = [])
	{
		$skip = false;
		$argumentKey = 0;
		foreach ($argumentArray as $key => $value)
		{
			$next = next($argumentArray);
			if (substr($value, 0, 1) === '-')
			{
				$offset = substr($value, 0, 2) === '--' ? 2 : 1;
				$optionArray = $this->_parseOption($value, $next, $offset);
				$skip = $optionArray['value'] === $next;
				$this->setOption($optionArray['key'], $optionArray['value']);
			}
			else if ($value && !$skip)
			{
				$this->setArgument($argumentKey++, $value);
			}
		}
	}

	/**
	 * parse raw option
	 *
	 * @since 3.0.0
	 *
	 * @param string $option raw option to be parsed
	 * @param string $next raw next to be parsed
	 * @param integer $offset offset of the raw option
	 *
	 * @return boolean
	 */

	protected function _parseOption($option = null, $next = null, $offset = null)
	{
		$equalPosition = strpos($option, '=');
		if ($equalPosition)
		{
			return
			[
				'key' => substr($option, $offset, $equalPosition - $offset),
				'value' => substr($option, $equalPosition + 1)
			];
		}
		return
		[
			'key' => substr($option, $offset),
			'value' => !$next || substr($next, 0, 1) === '-' ? true : $next
		];
	}
}
