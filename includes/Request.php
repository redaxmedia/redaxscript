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
	 * get the value from globals
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string $index index of the array
	 *
	 * @return string|array|bool
	 */

	public function get($key = null, $index = null)
	{
		/* handle index */

		if (is_array(self::$_requestArray) && array_key_exists($index, self::$_requestArray))
		{
			$requestArray = self::$_requestArray[$index];
		}
		else
		{
			$requestArray = self::$_requestArray;
		}

		/* values as needed */

		if (is_array($requestArray) && array_key_exists($key, $requestArray))
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
	 * get the value from server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|bool
	 */

	public function getServer($key = null)
	{
		return self::get($key, 'server');
	}

	/**
	 * get the value from query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|bool
	 */

	public function getQuery($key = null)
	{
		return self::get($key, 'get');
	}

	/**
	 * get the value from post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|bool
	 */

	public function getPost($key = null)
	{
		return self::get($key, 'post');
	}

	/**
	 * get the value from files
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|bool
	 */

	public function getFiles($key = null)
	{
		return self::get($key, 'files');
	}

	/**
	 * get the value from session
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|bool
	 */

	public function getSession($key = null)
	{
		return self::get($key, 'session');
	}

	/**
	 * get the value from cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|bool
	 */

	public function getCookie($key = null)
	{
		return self::get($key, 'cookie');
	}

	/**
	 * set the value to globals
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param array|bool $valueArray value of the item
	 */

	public function set($key = null, $valueArray = [])
	{
		self::$_requestArray[$key] = $GLOBALS[$key] = $valueArray;
	}

	/**
	 * set the value to server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|bool $value value of the item
	 */

	public function setServer($key = null, $value = null)
	{
		self::$_requestArray['server'][$key] = $value;
	}

	/**
	 * set the value to query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|bool $value value of the item
	 */

	public function setQuery($key = null, $value = null)
	{
		self::$_requestArray['get'][$key] = $value;
	}

	/**
	 * set the value to post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|bool $value value of the item
	 */

	public function setPost($key = null, $value = null)
	{
		self::$_requestArray['post'][$key] = $value;
	}

	/**
	 * set the value to files
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string|bool $value value of the item
	 */

	public function setFiles($key = null, $value = null)
	{
		self::$_requestArray['files'][$key] = $value;
	}

	/**
	 * set the value to session
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|bool $value value of the item
	 */

	public function setSession($key = null, $value = null)
	{
		self::$_requestArray['session'][$key] = $_SESSION[$key] = $value;
	}

	/**
	 * set the value to cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|bool $value value of the item
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
