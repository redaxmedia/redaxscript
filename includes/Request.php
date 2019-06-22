<?php
namespace Redaxscript;

use function array_key_exists;
use function is_array;

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

	public function init() : void
	{
		self::$_requestArray =
		[
			'server' => $_SERVER ? : [],
			'get' => $_GET ? : [],
			'post' => $_POST ? : [],
			'files' => $_FILES ? : [],
			'session' => $_SESSION ? : [],
			'cookie' => $_COOKIE ? : []
		];
	}

	/**
	 * get the value from globals
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function get(string $key = null)
	{
		if (is_array(self::$_requestArray) && array_key_exists($key, self::$_requestArray))
		{
			return self::$_requestArray[$key];
		}
		return null;
	}

	/**
	 * get the array from globals
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getArray() : array
	{
		return self::$_requestArray;
	}

	/**
	 * set the value to globals
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|null $value value of the item
	 */

	public function set(string $key = null, $value = null) : void
	{
		self::$_requestArray[$key] = $GLOBALS[$key] = $value;
	}

	/**
	 * get the value from server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function getServer(string $key = null)
	{
		if (is_array(self::$_requestArray['server']) && array_key_exists($key, self::$_requestArray['server']))
		{
			return self::$_requestArray['server'][$key];
		}
		return null;
	}

	/**
	 * set the value to server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|null $value value of the item
	 */

	public function setServer(string $key = null, $value = null) : void
	{
		self::$_requestArray['server'][$key] = $value;
	}

	/**
	 * get the value from query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function getQuery(string $key = null)
	{
		if (is_array(self::$_requestArray['get']) && array_key_exists($key, self::$_requestArray['get']))
		{
			return self::$_requestArray['get'][$key];
		}
		return null;
	}

	/**
	 * set the value to query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|null $value value of the item
	 */

	public function setQuery(string $key = null, $value = null) : void
	{
		self::$_requestArray['get'][$key] = $value;
	}

	/**
	 * get the value from post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function getPost(string $key = null)
	{
		if (is_array(self::$_requestArray['post']) && array_key_exists($key, self::$_requestArray['post']))
		{
			return self::$_requestArray['post'][$key];
		}
		return null;
	}

	/**
	 * set the value to post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|null $value value of the item
	 */

	public function setPost(string $key = null, $value = null) : void
	{
		self::$_requestArray['post'][$key] = $value;
	}

	/**
	 * get the value from files
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function getFiles(string $key = null)
	{
		if (is_array(self::$_requestArray['files']) && array_key_exists($key, self::$_requestArray['files']))
		{
			return self::$_requestArray['files'][$key];
		}
		return null;
	}

	/**
	 * set the value to files
	 *
	 * @since 3.0.0
	 *
	 * @param string $key key of the item
	 * @param string|array|null $value value of the item
	 */

	public function setFiles(string $key = null, $value = null) : void
	{
		self::$_requestArray['files'][$key] = $value;
	}

	/**
	 * get the value from session
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function getSession(string $key = null)
	{
		if (is_array(self::$_requestArray['session']) && array_key_exists($key, self::$_requestArray['session']))
		{
			return self::$_requestArray['session'][$key];
		}
		return null;
	}

	/**
	 * set the value to session
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|null $value value of the item
	 */

	public function setSession(string $key = null, $value = null) : void
	{
		self::$_requestArray['session'][$key] = $_SESSION[$key] = $value;
	}

	/**
	 * refresh the session
	 *
	 * @since 2.6.2
	 */

	public function refreshSession() : void
	{
		self::$_requestArray['session'] = $_SESSION ? : [];
	}

	/**
	 * get the value from cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 *
	 * @return string|array|null
	 */

	public function getCookie(string $key = null)
	{
		if (is_array(self::$_requestArray['cookie']) && array_key_exists($key, self::$_requestArray['cookie']))
		{
			return self::$_requestArray['cookie'][$key];
		}
		return null;
	}

	/**
	 * set the value to cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string|array|null $value value of the item
	 */

	public function setCookie(string $key = null, $value = null) : void
	{
		self::$_requestArray['cookie'][$key] = $_COOKIE[$key] = $value;
	}

	/**
	 * refresh the cookie
	 *
	 * @since 2.6.2
	 */

	public function refreshCookie() : void
	{
		self::$_requestArray['cookie'] = $_COOKIE ? : [];
	}
}
