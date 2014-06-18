<?php

/**
 * parent class to request super globals
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Request
 * @author Henry Ruhs
 */

class Redaxscript_Request
{
	/**
	 * array of request values
	 *
	 * @var array
	 */

	protected static $_values = array(
		'server' => '_SERVER',
		'query' => '_GET',
		'post' => '_POST',
		'session' => '_SESSION',
		'coookie' => '_COOKIE'
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 */

	private function __construct()
	{
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.2.0
	 */

	public function init()
	{
		foreach (self::$_values as $key => $value)
		{
			if (isset($$value))
			{
				self::$_values[$key] = $$value;
			}
			else
			{
				self::$_values[$key] = null;
			}
		}
	}

	/**
	 * get item from request
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param string $index index of the key array
	 *
	 * @return string
	 */

	public static function get($key = null, $index = null)
	{
		$output = null;

		/* handle index */

		if (isset($index) && is_array(self::$_values[$index]))
		{
			$values = self::$_values[$index];
		}
		else
		{
			$values = self::$_values;
		}

		/* values as needed */

		if (is_null($key))
		{
			$output = $values;
		}
		else if (array_key_exists($key, $values))
		{
			$output = $values[$key];
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
		$output = self::get($key, 'query');
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
	 * set item to server
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function setServer($key = null, $value = null)
	{
		self::$_values['server'][$key] = $value;
	}

	/**
	 * set item to query
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function setQuery($key = null, $value = null)
	{
		self::$_values['query'][$key] = $value;
	}

	/**
	 * set item to post
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function setPost($key = null, $value = null)
	{
		self::$_values['post'][$key] = $value;
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
		self::$_values['session'][$key] = $value;
	}

	/**
	 * set item to cookie
	 *
	 * @since 2.2.0
	 *
	 * @param string $key key of the item
	 * @param mixed $value value of the item
	 */

	public static function setCookie($key = null, $value = null)
	{
		self::$_values['cookie'][$key] = $value;
	}
}
