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
	 * @var object
	 */

	protected $_request;

	/**
	 * array of the parameter
	 *
	 * @var array
	 */

	protected $_parameterArray = array();

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
		$this->_parameterArray = array_filter(explode('/', $this->_request->getQuery('p')));
		if (is_array($this->_parameterArray))
		{
			$aliasFilter = new Filter\Alias;
			$this->_parameterArray = array_map(array(
				$aliasFilter,
				'sanitize'
			), $this->_parameterArray);
		}
	}

	/**
	 * get the first parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getFirst()
	{
		if (array_key_exists(0, $this->_parameterArray) && !is_numeric($this->_parameterArray[0]))
		{
			return $this->_parameterArray[0];
		}
	}

	/**
	 * get the second parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getSecond()
	{
		if (array_key_exists(1, $this->_parameterArray) && !is_numeric($this->_parameterArray[1]))
		{
			return $this->_parameterArray[1];
		}
	}

	/**
	 * get the third parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getThird()
	{
		if (array_key_exists(2, $this->_parameterArray) && !is_numeric($this->_parameterArray[2]))
		{
			return $this->_parameterArray[2];
		}
	}

	/**
	 * get the fourth parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getFourth()
	{
		if (array_key_exists(3, $this->_parameterArray) && !is_numeric($this->_parameterArray[3]))
		{
			return $this->_parameterArray[3];
		}
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
		if (!is_numeric(end($this->_parameterArray)))
		{
			return end($this->_parameterArray);
		}
		return prev($this->_parameterArray);
	}

	/**
	 * get the sub parameter
	 *
	 * @since 2.4.0
	 *
	 * @return string
	 */

	public function getSub()
	{
		foreach ($this->_parameterArray as $subParameter)
		{
			if (is_numeric($subParameter))
			{
				return $subParameter;
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
		if ($this->getTable() && $this->getSub())
		{
			return $this->getSub();
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
}