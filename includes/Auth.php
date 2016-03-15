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

	protected $_selectArray = array(
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
	 * @return integer
	 */

	public function __call($method = null, $arguments = array())
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
	 * @param integer $id id of the user
	 *
	 * @since 3.0.0
	 */

	public function login($id = null)
	{
		$user = Db::forTablePrefix('users')->whereIdIs($id)->findOne();
		$groupArray = array_map('intval', explode(',', $user->groups));
		$group = Db::forTablePrefix('groups')->whereIdIn($groupArray)->where('status', 1)->select($this->_selectArray)->findArray();

		/* process group */

		foreach ($group as $key => $value)
		{
			foreach ($value as $keySub => $valueSub)
			{
				$valueArray = array_map('intval', explode(',', $valueSub));
				$this->_setPermission($keySub, $valueArray);
			}
		}

		/* set to session */

		$this->_request->setSession('auth', array(
			'user' => array(
				'id' => $user->id,
				'name' => $user->name,
				'user' => $user->user,
				'email' => $user->email,
				'user' => $user->user,
				'groups' => $user->groups
			),
			'permission' => $this->_permissionArray
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

	protected function _setUser($key = null, $value = null)
	{
		$this->_userArray[$key] = $value;
	}

	/**
	 * get the permission
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 *
	 * @return mixed
	 */

	public function getPermission($table = null)
	{
		if (!$table)
		{
			return $this->_permissionArray;
		}
		else if (array_key_exists($table, $this->_permissionArray))
		{
			return $this->_permissionArray[$table];
		}
		return false;
	}

	/**
	 * set the permission
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 * @param integer $value value of the permission
	 */

	protected function _setPermission($table = null, $value = null)
	{
		$this->_permissionArray[$table] = $value;
	}
}