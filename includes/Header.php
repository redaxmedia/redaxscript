<?php
namespace Redaxscript;

/**
 * children class to add and remove the header
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Header
 * @author Henry Ruhs
 */

class Header
{
	/**
	 * init the class
	 *
	 * @since 3.3.0
	 */

	public static function init()
	{
		self::add(
		[
			'X-Content-Type-Options: nosniff',
			'X-Frame-Options: sameorigin',
			'X-XSS-Protection: 1; mode=block'
		]);
		self::remove('X-Powered-By');
	}

	/**
	 * add the header
	 *
	 * @since 3.3.0
	 *
	 * @param string|array $header
	 * @param bool $replace
	 *
	 * @return bool
	 */

	public static function add($header = null, bool $replace = true) : bool
	{
		if (!self::isSent())
		{
			foreach ((array)$header as $value)
			{
				header($value, $replace);
			}
			return true;
		}
		return false;

	}

	/**
	 * remove the header
	 *
	 * @since 3.3.0
	 *
	 * @param string|array $header
	 *
	 * @return bool
	 */

	public static function remove($header = null) : bool
	{
		if (!self::isSent())
		{
			foreach ((array)$header as $value)
			{
				header_remove($value);
			}
			return true;
		}
		return false;
	}

	/**
	 * is header sent
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	public static function isSent() : bool
	{
		return headers_sent();
	}

	/**
	 * get the header array
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public static function getArray() : array
	{
		return headers_list();
	}

	/**
	 * status code
	 *
	 * @since 3.3.0
	 *
	 * @param int $code
	 *
	 * @return int
	 */

	public static function statusCode(int $code = null) : int
	{
		return http_response_code($code);
	}

	/**
	 * redirect to location
	 *
	 * @since 3.3.0
	 *
	 * @param string $location
	 *
	 * @return bool
	 */

	public static function doRedirect(string $location = null) : bool
	{
		return self::add('Location: ' . $location);
	}

	/**
	 * content type
	 *
	 * @since 3.3.0
	 *
	 * @param string $type
	 *
	 * @return bool
	 */

	public static function contentType(string $type = null) : bool
	{
		return self::add('Content-Type: ' . $type);
	}
}
