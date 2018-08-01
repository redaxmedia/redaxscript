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
 * @method bool getPermissionNew(string $type)
 * @method bool getPermissionInstall(string $type)
 * @method bool getPermissionEdit(string $type)
 * @method bool getPermissionDelete(string $type)
 * @method bool getPermissionUninstall(string $type)
 * @method bool getFilter()
 */

class Auth
{
	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * array of the user
	 *
	 * @var array
	 */

	protected $_userArray = [];

	/**
	 * array of the permission
	 *
	 * @var array
	 */

	protected $_permissionArray = [];

	/**
	 * array of the type
	 *
	 * @var array
	 */

	protected $_typeArray =
	[
		'categories',
		'articles',
		'extras',
		'comments',
		'groups',
		'users',
		'modules',
		'settings',
		'filter'
	];

	/**
	 * array of the call
	 *
	 * @var array
	 */

	protected $_callArray =
	[
		'categories' =>
		[
			'getPermissionNew' => 1,
			'getPermissionEdit' => 2,
			'getPermissionDelete' => 3
		],
		'articles' =>
		[
			'getPermissionNew' => 1,
			'getPermissionEdit' => 2,
			'getPermissionDelete' => 3
		],
		'extras' =>
		[
			'getPermissionNew' => 1,
			'getPermissionEdit' => 2,
			'getPermissionDelete' => 3
		],
		'comments' =>
		[
			'getPermissionNew' => 1,
			'getPermissionEdit' => 2,
			'getPermissionDelete' => 3
		],
		'groups' =>
		[
			'getPermissionNew' => 1,
			'getPermissionEdit' => 2,
			'getPermissionDelete' => 3
		],
		'users' =>
		[
			'getPermissionNew' => 1,
			'getPermissionEdit' => 2,
			'getPermissionDelete' => 3
		],
		'modules' =>
		[
			'getPermissionInstall' => 1,
			'getPermissionEdit' => 2,
			'getPermissionUninstall' => 3
		],
		'settings' =>
		[
			'getPermissionEdit' => 1
		],
		'filter' =>
		[
			'getFilter' => 0
		]
	];

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
	 * @param array $argumentArray arguments of the method
	 *
	 * @return bool
	 */

	public function __call($method = null, array $argumentArray = []) : bool
	{
		$type = $argumentArray[0];
		if (is_array($this->_callArray[$type]) && array_key_exists($method, $this->_callArray[$type]))
		{
			$permissionArray = $this->getPermission($type);
			return is_array($permissionArray) && in_array($this->_callArray[$type][$method], $permissionArray);
		}
		if ($method === 'getFilter')
		{
			$permissionArray = $this->getPermission('filter');
			return !is_array($permissionArray) || !in_array($this->_callArray['filter'][$method], $permissionArray);
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
		$authArray = $this->_getAuth();
		if (is_array($authArray) && array_key_exists('user', $authArray))
		{
			$this->_userArray = $authArray['user'];
		}
		if (is_array($authArray) && array_key_exists('permission', $authArray))
		{
			$this->_permissionArray = $authArray['permission'];
		}
	}

	/**
	 * login the user
	 *
	 * @since 3.0.0
	 *
	 * @param int $userId identifier of the user
	 *
	 * @return int
	 */

	public function login(int $userId = null) : int
	{
		$user = Db::forTablePrefix('users')
			->whereIdIs($userId)
			->where('status', 1)
			->findOne();

		/* handle user */

		if ($user->user && $user->password)
		{
			$groupArray = (array)json_decode($user->groups);
			$group = Db::forTablePrefix('groups')
				->whereIdIn($groupArray)
				->where('status', 1)
				->select($this->_typeArray)
				->findArray();

			/* set the filter */

			$this->setPermission('filter',
			[
				1
			]);

			/* process groups */

			foreach ($group as $value)
			{
				foreach ($value as $keySub => $valueSub)
				{
					$valueArray = (array)json_decode($valueSub);
					$this->setPermission($keySub, $valueArray);
				}
			}

			/* set the user */

			$this->setUser('id', $user->id);
			$this->setUser('name', $user->name);
			$this->setUser('user', $user->user);
			$this->setUser('email', $user->email);
			$this->setUser('language', $user->language);
			$this->setUser('groups', $user->groups);

			/* save user and permission */

			$this->save();
		}
		return $this->getStatus();
	}

	/**
	 * logout the user
	 *
	 * @since 3.0.0
	 *
	 * @return bool
	 */

	public function logout() : bool
	{
		if ($this->getStatus())
		{
			$this->_setAuth();
			return !$this->getStatus();
		}
		return false;
	}

	/**
	 * get the user
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the user
	 *
	 * @return string|array|bool
	 */

	public function getUser(string $key = null)
	{
		if (is_array($this->_userArray) && array_key_exists($key, $this->_userArray))
		{
			return $this->_userArray[$key];
		}
		else if (!$key)
		{
			return $this->_userArray;
		}
		return false;
	}

	/**
	 * set the user
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the user
	 * @param string|array|bool $value value of the user
	 */

	public function setUser(string $key = null, $value = null)
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
	 * @return string|array|bool
	 */

	public function getPermission(string $key = null)
	{
		if (is_array($this->_permissionArray) && array_key_exists($key, $this->_permissionArray))
		{
			return $this->_permissionArray[$key];
		}
		else if (!$key)
		{
			return $this->_permissionArray;
		}
		return false;
	}

	/**
	 * set the permission
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the permission
	 * @param array $permissionArray array of the permission
	 */

	public function setPermission(string $key = null, array $permissionArray = [])
	{
		if (is_array($this->_permissionArray[$key]))
		{
			$permissionArray = array_merge($this->_permissionArray[$key], $permissionArray);
		}
		$this->_permissionArray[$key] = $permissionArray;
	}

	/**
	 * get the auth status
	 *
	 * @since 3.0.0
	 *
	 * @return int
	 */

	public function getStatus() : int
	{
		$authArray = $this->_getAuth();
		return is_array($authArray) && array_key_exists('user', $authArray) && array_key_exists('permission', $authArray) ? 1 : 0;
	}

	/**
	 * save user and permission
	 *
	 * @since 3.0.0
	 */

	public function save()
	{
		$userArray = $this->getUser();
		$permissionArray = $this->getPermission();

		/* set the session */

		if ($userArray && $permissionArray)
		{
			$this->_setAuth(
			[
				'user' => $userArray,
				'permission' => $permissionArray
			]);
			if ($userArray['language'])
			{
				$this->_request->setSession('language', $userArray['language']);
			}
		}
	}

	/**
	 * get the auth from session
	 *
	 * @since 3.0.0
	 *
	 * @return array|bool
	 */

	protected function _getAuth()
	{
		$root = new Server\Root($this->_request);
		return $this->_request->getSession($root->getOutput() . '/auth');
	}

	/**
	 * set the auth to session
	 *
	 * @since 3.0.0
	 *
	 * @param array $authArray
	 */

	protected function _setAuth(array $authArray = [])
	{
		$root = new Server\Root($this->_request);
		return $this->_request->setSession($root->getOutput() . '/auth', $authArray);
	}
}
