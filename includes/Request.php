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

	public static function init()
	{
		self::$_requestArray =
		[
			'server' => $_SERVER ? $_SERVER : [],
			'get' => $_GET ? $_GET : [],
			'post' => $_POST ? $_POST : [],
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

	public static function get($key = null, $index = null)
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

	public static function getServer($key = null)
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

	public static function getQuery($key = null)
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

	public static function getPost($key = null)
	{
		return self::get($key, 'post');
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

	public static function getSession($key = null)
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

	public static function getCookie($key = null)
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

	public static function set($key = null, $value = null)
	{
		self::$_requestArray[$key] = $GLOBALS[$key] = $value;
	}

	/**
	 * set item to server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 */

	public static function setServer($key = null, $value = null)
	{
		self::$_requestArray['server'][$key] = $value;
	}

	/**
	 * set item to query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 */

	public static function setQuery($key = null, $value = null)
	{
		self::$_requestArray['get'][$key] = $value;
	}

	/**
	 * set item to post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 */

	public static function setPost($key = null, $value = null)
	{
		self::$_requestArray['post'][$key] = $value;
	}

	/**
	 * set item to session
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function setSession($key = null, $value = null)
	{
		self::$_requestArray['session'][$key] = $_SESSION[$key] = $value;
	}

	/**
	 * set item to cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $value value of the item
	 */

	public static function setCookie($key = null, $value = null)
	{
		self::$_requestArray['cookie'][$key] = $_COOKIE[$key] = $value;
	}

	/**
	 * refresh the session
	 *
	 * @since 2.6.2
	 */

	public static function refreshSession()
	{
		self::$_requestArray['session'] = $_SESSION ? $_SESSION : [];
	}

	/**
	 * refresh the cookie
	 *
	 * @since 2.6.2
	 */

	public static function refreshCookie()
	{
		self::$_requestArray['cookie'] = $_COOKIE ? $_COOKIE : [];
	}
}
