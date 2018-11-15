<?php
namespace Redaxscript\Router;

use Redaxscript\Filter;
use Redaxscript\Request;
use Redaxscript\Server;
use function array_filter;
use function array_key_exists;
use function array_map;
use function array_reverse;
use function explode;
use function is_array;
use function is_numeric;

/**
 * parent class to get the parameter
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Router
 * @author Henry Ruhs
 */

class Parameter
{
	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * array of the parameter
	 *
	 * @var array
	 */

	protected $_parameterArray = [];

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
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
	 * @since 2.4.0
	 */

	public function init()
	{
		$parameter = $this->_request->getQuery('p');
		$this->_parameterArray = array_filter(explode('/', $parameter), 'strlen');
		if (is_array($this->_parameterArray))
		{
			$aliasFilter = new Filter\Alias;
			$this->_parameterArray = array_map(
			[
				$aliasFilter,
				'sanitize'
			], $this->_parameterArray);
		}
	}

	/**
	 * get the first parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string|null
	 */

	public function getFirst() : ?string
	{
		return $this->_getParameter(0);
	}

	/**
	 * get the first sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return int|null
	 */

	public function getFirstSub() : ?int
	{
		return $this->_getParameterSub(1);
	}

	/**
	 * get the second parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string|null
	 */

	public function getSecond() : ?string
	{
		return $this->_getParameter(1);
	}

	/**
	 * get the second sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return int|null
	 */

	public function getSecondSub() : ?int
	{
		return $this->_getParameterSub(2);
	}

	/**
	 * get the third parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string|null
	 */

	public function getThird() : ?string
	{
		return $this->_getParameter(2);
	}

	/**
	 * get the third sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return int|null
	 */

	public function getThirdSub() : ?int
	{
		return $this->_getParameterSub(3);
	}

	/**
	 * get the fourth parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string|null
	 */

	public function getFourth() : ?string
	{
		return $this->_getParameter(3);
	}

	/**
	 * get the fourth sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return int|null
	 */

	public function getFourthSub() : ?int
	{
		return $this->_getParameterSub(4);
	}

	/**
	 * get the last parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getLast() : ?string
	{
		foreach (array_reverse($this->_parameterArray) as $value)
		{
			if (!is_numeric($value))
			{
				return $value;
			}
		}
		return null;
	}

	/**
	 * get the last sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return int|null
	 */

	public function getLastSub() : ?int
	{
		foreach (array_reverse($this->_parameterArray) as $value)
		{
			if (is_numeric($value))
			{
				return $value;
			}
		}
		return null;
	}

	/**
	 * get the admin parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getAdmin() : ?string
	{
		if ($this->getFirst() === 'admin' && $this->getSecond())
		{
			return $this->getSecond();
		}
		return null;
	}

	/**
	 * get the table parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getTable() : ?string
	{
		if ($this->getAdmin() && $this->getThird())
		{
			return $this->getThird();
		}
		return null;
	}

	/**
	 * get the alias parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getAlias() : ?string
	{
		if ($this->getAdmin() && $this->getFourth())
		{
			return $this->getFourth();
		}
		return null;
	}

	/**
	 * get the id parameter
	 *
	 * @since 2.4.0
	 *
	 * @return int|null
	 */

	public function getId() : ?int
	{
		if ($this->getTable() && $this->getThirdSub())
		{
			return $this->getThirdSub();
		}
		return null;
	}

	/**
	 * get the token parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getToken() : ?string
	{
		$token = new Server\Token($this->_request);
		if ($this->getLast() === $token->getOutput())
		{
			return $this->getLast();
		}
		return null;
	}

	/**
	 * get the parameter by key
	 *
	 * @since 3.1.0
	 *
	 * @param int $key
	 *
	 * @return string|null
	 */

	protected function _getParameter(int $key = null) : ?string
	{
		if (is_array($this->_parameterArray) && array_key_exists($key, $this->_parameterArray) && !is_numeric($this->_parameterArray[$key]))
		{
			return $this->_parameterArray[$key];
		}
		return null;
	}

	/**
	 * get the parameter sub by key
	 *
	 * @since 3.1.0
	 *
	 * @param int $key
	 *
	 * @return int|null
	 */

	protected function _getParameterSub(int $key = null) : ?int
	{
		if (is_array($this->_parameterArray) && array_key_exists($key, $this->_parameterArray) && is_numeric($this->_parameterArray[$key]))
		{
			return $this->_parameterArray[$key];
		}
		return null;
	}
}
