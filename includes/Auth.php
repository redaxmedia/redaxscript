<?php
namespace Redaxscript;

/**
 * parent class to authenticate the user
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Auth
 * @author Henry Ruhs
 */

class Auth
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * array of the user
	 *
	 * @var object
	 */

	protected $_user;

	/**
	 * array of the permission
	 *
	 * @var array
	 */

	protected $_permissionArray = array();

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
	 * call method as needed
	 *
	 * @since 3.0.0
	 *
	 * @param string $method name of the method
	 *
	 * @return integer
	 */

	public function __call($method = null)
	{
		if ($method === 'getNewInArticles')
		{
			return $this->getPermission('articles', 'new');
		}
	}

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 */

	public function init()
	{
		//fetch and set the stored user and permission from the session
	}

	/**
	 * login the user
	 *
	 * @param string $user
	 * @param string $password
	 *
	 * @since 3.0.0
	 */

	public function login($user = null, $password = null)
	{
	}

	/**
	 * logout the user
	 *
	 * @since 3.0.0
	 */

	public function logout()
	{
		//destroy user and permission in the session
	}

	/**
	 * get the user
	 *
	 * @since 3.0.0
	 *
	 * @return object
	 */

	public function getUser()
	{
		return $this->_user;
	}

	/**
	 * get the permission
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 * @param string $type type of the permission
	 *
	 * @return mixed
	 */

	public function getPermission($table = null, $type = null)
	{
		if (!$table)
		{
			return $this->_permissionArray;
		}
		else if (array_key_exists($type, $this->_permissionArray[$table]))
		{
			return $this->_permissionArray[$table][$type];
		}
		return false;
	}

	/**
	 * set the permission
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 * @param string $type type of the permission
	 * @param integer $value value of the permission
	 */

	protected function _setPermission($table = null, $type = null, $value = null)
	{
		$this->_permissionArray[$table][$type] = $value;
	}
}