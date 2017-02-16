<?php
namespace Redaxscript;

/**
 * children class to request globals
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Request
 * @author Henry Ruhs
 */

class Request extends Singleton
{
	/**
	 * array of the request
	 *
	 * @var array
	 */

	protected static $_requestArray = [];

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 */

	public function init()
	{
		self::$_requestArray =
		[
			'server' => $_SERVER ? $_SERVER : [],
			'get' => $_GET ? $_GET : [],
			'post' => $_POST ? $_POST : [],
			'files' => $_FILES ? $_FILES : [],
			'session' => $_SESSION ? $_SESSION : [],
			'cookie' => $_COOKIE ? $_COOKIE : []
		];
	}

	/**
	 * get item from globals
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string $index index of the array
	 *
	 * @return mixed
	 */

	public function get($key = null, $index = null)
	{
		/* handle index */

		if (array_key_exists($index, self::$_requestArray))
		{
			$requestArray = self::$_requestArray[$index];
		}
		else
		{
			$requestArray = self::$_requestArray;
		}

		/* values as needed */

		if (array_key_exists($key, $requestArray))
		{
			return $requestArray[$key];
		}
		else if (!$key)
		{
			return $requestArray;
		}
		return false;
	}

	/**
	 * get item from server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public function getServer($key = null)
	{
		return self::get($key, 'server');
	}

	/**
	 * get item from query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public function getQuery($key = null)
	{
		return self::get($key, 'get');
	}

	/**
	 * get item from post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public function getPost($key = null)
	{
		return self::get($key, 'post');
	}

	/**
	 * get item from files
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public function getFiles($key = null)
	{
		return self::get($key, 'files');
	}

	/**
	 * get item from session
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public function getSession($key = null)
	{
		return self::get($key, 'session');
	}

	/**
	 * get item from cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string
	 */

	public function getCookie($key = null)
	{
		return self::get($key, 'cookie');
	}

	/**
	 * set item to globals
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function set($key = null, $value = null)
	{
		self::$_requestArray[$key] = $GLOBALS[$key] = $value;
	}

	/**
	 * set item to server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setServer($key = null, $value = null)
	{
		self::$_requestArray['server'][$key] = $value;
	}

	/**
	 * set item to query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setQuery($key = null, $value = null)
	{
		self::$_requestArray['get'][$key] = $value;
	}

	/**
	 * set item to post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setPost($key = null, $value = null)
	{
		self::$_requestArray['post'][$key] = $value;
	}

	/**
	 * set item to files
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setFiles($key = null, $value = null)
	{
		self::$_requestArray['files'][$key] = $value;
	}

	/**
	 * set item to session
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setSession($key = null, $value = null)
	{
		self::$_requestArray['session'][$key] = $_SESSION[$key] = $value;
	}

	/**
	 * set item to cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public function setCookie($key = null, $value = null)
	{
		self::$_requestArray['cookie'][$key] = $_COOKIE[$key] = $value;
	}

	/**
	 * refresh the session
	 *
	 * @since 2.6.2
	 */

	public function refreshSession()
	{
		self::$_requestArray['session'] = $_SESSION ? $_SESSION : [];
	}

	/**
	 * refresh the cookie
	 *
	 * @since 2.6.2
	 */

	public function refreshCookie()
	{
		self::$_requestArray['cookie'] = $_COOKIE ? $_COOKIE : [];
	}
}
