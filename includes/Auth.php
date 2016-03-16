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
 *
 * @method getPermissionNew
 * @method getPermissionInstall
 * @method getPermissionEdit
 * @method getPermissionDelete
 * @method getPermissionUninstall
 * @method getFilter
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
	 * @var array
	 */

	protected $_userArray = array();

	/**
	 * array of the permission
	 *
	 * @var array
	 */

	protected $_permissionArray = array();

	/**
	 * array of the select
	 *
	 * @var array
	 */

	protected $_typeArray = array(
		'categories',
		'articles',
		'extras',
		'comments',
		'groups',
		'users',
		'modules',
		'settings',
		'filter'
	);

	/**
	 * array of the call
	 *
	 * @var array
	 */

	protected $_callArray = array(
		'getPermissionNew' => 1,
		'getPermissionInstall' => 1,
		'getPermissionEdit' => 2,
		'getPermissionDelete' => 3,
		'getPermissionUninstall' => 3,
		'getFilter' => 1
	);

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
	 * @param array $arguments arguments of the method
	 *
	 * @return mixed
	 */

	public function __call($method = null, $arguments = array())
	{
		if (array_key_exists($method, $this->_callArray))
		{
			$permissionArray = $this->getPermission($arguments[0]);
			if (array_key_exists($this->_callArray[$method], $permissionArray))
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 */

	public function init()
	{
		$authArray = $this->_request->getSession('auth');
		if (array_key_exists('user', $authArray['user']))
		{
			$this->_userArray = $authArray['user'];
		}
		if (array_key_exists('permission', $authArray['permission']))
		{
			$this->_userArray = $authArray['permission'];
		}
	}

	/**
	 * login the user
	 *
	 * @param integer $userId identifier of the user
	 *
	 * @return mixed
	 *
	 * @since 3.0.0
	 */

	public function login($userId = null)
	{
		$user = Db::forTablePrefix('users')->whereIdIs($userId)->findOne();
		$groupArray = array_map('intval', explode(',', $user->groups));
		$group = Db::forTablePrefix('groups')->whereIdIn($groupArray)->where('status', 1)->select($this->_typeArray)->findArray();

		/* process group */

		foreach ($group as $key => $value)
		{
			foreach ($value as $keySub => $valueSub)
			{
				$valueArray = array_map('intval', explode(',', $valueSub));
				$this->setPermission($keySub, $valueArray);
			}
		}

		/* set user */

		$this->setUser('id', $user->id);
		$this->setUser('name', $user->name);
		$this->setUser('user', $user->user);
		$this->setUser('email', $user->email);
		$this->setUser('groups', $user->groups);

		/* set to session */

		$this->_request->setSession('auth', array(
			'user' => $this->getUser(),
			'permission' => $this->getPermission()
		));
	}

	/**
	 * logout the user
	 *
	 * @since 3.0.0
	 */

	public function logout()
	{
		$this->_request->setSession('auth', null);
	}

	/**
	 * get the user
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the user
	 *
	 * @return mixed
	 */

	public function getUser($key = null)
	{
		if (!$key)
		{
			return $this->_userArray;
		}
		else if (array_key_exists($key, $this->_userArray))
		{
			return $this->_userArray[$key];
		}
		return false;
	}

	/**
	 * set the user
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the user
	 * @param string $value value of the user
	 */

	public function setUser($key = null, $value = null)
	{
		$this->_userArray[$key] = $value;
	}

	/**
	 * get the permission
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the permission
	 *
	 * @return mixed
	 */

	public function getPermission($key = null)
	{
		if (!$key)
		{
			return $this->_permissionArray;
		}
		else if (array_key_exists($key, $this->_permissionArray))
		{
			return $this->_permissionArray[$key];
		}
		return false;
	}

	/**
	 * set the permission
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the permission
	 * @param integer $value value of the permission
	 */

	public function setPermission($key = null, $value = null)
	{
		$this->_permissionArray[$key] = $value;
	}
}
