<?php
namespace Redaxscript\Router;

use Redaxscript\Filter;
use Redaxscript\Server;
use Redaxscript\Request;

/**
 * parent class to get parameter
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
		$this->_parameterArray = array_filter(explode('/', $parameter));
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
	 * @return string
	 */

	public function getFirst()
	{
		return $this->_getParameter(0);
	}

	/**
	 * get the first sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return integer
	 */

	public function getFirstSub()
	{
		return $this->_getParameterSub(1);
	}

	/**
	 * get the second parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */

	public function getSecond()
	{
		return $this->_getParameter(1);
	}

	/**
	 * get the second sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return integer
	 */

	public function getSecondSub()
	{
		return $this->_getParameterSub(2);
	}

	/**
	 * get the third parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */

	public function getThird()
	{
		return $this->_getParameter(2);
	}

	/**
	 * get the third sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return integer
	 */

	public function getThirdSub()
	{
		return $this->_getParameterSub(3);
	}

	/**
	 * get the fourth parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */

	public function getFourth()
	{
		return $this->_getParameter(3);
	}

	/**
	 * get the fourth sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */

	public function getFourthSub()
	{
		return $this->_getParameterSub(4);
	}

	/**
	 * get the last parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getLast()
	{
		foreach (array_reverse($this->_parameterArray) as $value)
		{
			if (!is_numeric($value))
			{
				return $value;
			}
		}
	}

	/**
	 * get the last sub parameter
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */

	public function getLastSub()
	{
		foreach (array_reverse($this->_parameterArray) as $value)
		{
			if (is_numeric($value))
			{
				return $value;
			}
		}
	}

	/**
	 * get the admin parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getAdmin()
	{
		if ($this->getFirst() === 'admin' && $this->getSecond())
		{
			return $this->getSecond();
		}
	}

	/**
	 * get the table parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getTable()
	{
		if ($this->getAdmin() && $this->getThird())
		{
			return $this->getThird();
		}
	}

	/**
	 * get the alias parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getAlias()
	{
		if ($this->getAdmin() && $this->getFourth())
		{
			return $this->getFourth();
		}
	}

	/**
	 * get the id parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getId()
	{
		if ($this->getTable() && $this->getThirdSub())
		{
			return $this->getThirdSub();
		}
	}

	/**
	 * get the token parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getToken()
	{
		$token = new Server\Token($this->_request);
		if ($this->getLast() === $token->getOutput())
		{
			return $this->getLast();
		}
	}

	/**
	 * get the parameter by key
	 *
	 * @since 3.1.0
	 *
	 * @param integer $key
	 *
	 * @return string
	 */

	protected function _getParameter($key = null)
	{
		if (array_key_exists($key, $this->_parameterArray) && !is_numeric($this->_parameterArray[$key]))
		{
			return $this->_parameterArray[$key];
		}
	}

	/**
	 * get the parameter sub by key
	 *
	 * @since 3.1.0
	 *
	 * @param integer $key
	 *
	 * @return integer
	 */

	protected function _getParameterSub($key = null)
	{
		if (array_key_exists($key, $this->_parameterArray) && is_numeric($this->_parameterArray[$key]))
		{
			return $this->_parameterArray[$key];
		}
	}
}