<?php
namespace Redaxscript\Console;

use Redaxscript\Request;
use function array_filter;
use function array_key_exists;
use function explode;
use function is_array;
use function next;
use function strpos;
use function substr;

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

	public function init(string $mode = null) : void
	{
		if ($mode === 'cli')
		{
			$argumentArray = $this->_request->getServer('argv');
			unset($argumentArray[0]);
		}
		if ($mode === 'template')
		{
			$argumentArray = array_filter(explode(' ', $this->_request->getStream('argv')));
		}
		$this->_parseArgument($argumentArray);
	}

	/**
	 * get the value from arguments
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|null
	 */

	public function getArgument(string $key = null) : ?string
	{
		if (is_array($this->_argumentArray) && array_key_exists($key, $this->_argumentArray))
		{
			return $this->_argumentArray[$key];
		}
		return null;
	}

	/**
	 * get the array from arguments
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getArgumentArray() : array
	{
		return $this->_argumentArray;
	}

	/**
	 * get the value from options
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|null
	 */

	public function getOption(string $key = null) : ?string
	{
		if (is_array($this->_optionArray) && array_key_exists($key, $this->_optionArray))
		{
			return $this->_optionArray[$key];
		}
		return null;
	}

	/**
	 * get the array from options
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getOptionArray() : array
	{
		return $this->_optionArray;
	}

	/**
	 * set the value to arguments
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string|int $value value of the item
	 */

	public function setArgument(string $key = null, $value = null) : void
	{
		$this->_argumentArray[$key] = $value;
	}

	/**
	 * set the value to options
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string|int $value value of the item
	 */

	public function setOption(string $key = null, $value = null) : void
	{
		$this->_optionArray[$key] = $value;
	}

	/**
	 * parse raw argument
	 *
	 * @since 3.0.0
	 *
	 * @param array|null $argumentArray raw argument to be parsed
	 */

	protected function _parseArgument(?array $argumentArray = []) : void
	{
		$doSkip = false;
		$argumentKey = 0;
		if (is_array($argumentArray))
		{
			foreach ($argumentArray as $value)
			{
				$next = next($argumentArray);
				if (strpos($value, '-') === 0)
				{
					$offset = strpos($value, '--') === 0 ? 2 : 1;
					$optionArray = $this->_parseOption($value, $next, $offset);
					$doSkip = $optionArray['value'] === $next;
					$this->setOption($optionArray['key'], $optionArray['value']);
				}
				else if ($value && !$doSkip)
				{
					$this->setArgument($argumentKey++, $value);
				}
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
	 * @param int $offset offset of the raw option
	 *
	 * @return array
	 */

	protected function _parseOption(string $option = null, string $next = null, int $offset = null) : array
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
			'value' => !$next || strpos($next, '-') === 0 ? true : $next
		];
	}
}
