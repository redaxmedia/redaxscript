<?php
namespace Redaxscript;

/**
 * parent class to request globals
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

	protected static $_requestArray = array();

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 */

	public static function init()
	{
		self::$_requestArray = array(
			'server' => $_SERVER,
			'get' => $_GET,
			'post' => $_POST,
			'cookie' => $_COOKIE
		);
	}

	/**
	 * get item from globals
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $index index of the key array
	 *
	 * @return mixed
	 */

	public static function get($key = null, $index = null)
	{
		$output = false;

		/* handle index */

		if (isset($index) && isset(self::$_requestArray[$index]))
		{
			$requestArray = self::$_requestArray[$index];
		}
		else
		{
			$requestArray = self::$_requestArray;
		}

		/* output as needed */

		if (is_null($key))
		{
			$output = $requestArray;
		}
		else if (array_key_exists($key, $requestArray))
		{
			$output = $requestArray[$key];
		}
		return $output;
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
		$output = self::get($key, 'server');
		return $output;
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
		$output = self::get($key, 'get');
		return $output;
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
		$output = self::get($key, 'post');
		return $output;
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
		$output = self::get($key, 'session');
		return $output;
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
		$output = self::get($key, 'cookie');
		return $output;
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
}
