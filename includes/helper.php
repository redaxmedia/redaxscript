<?php

/**
 * Redaxscript Helper
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Helper
 * @author Henry Ruhs
 * @author Kim Kha Nguyen
 */

class Redaxscript_Helper
{
	/**
	 * subsetCyrillic
	 *
	 * @var array
	 */

	protected static $_subsetCyrillic = array(
		'bg',
		'ru'
	);

	/**
	 * subsetVietnamese
	 *
	 * @var array
	 */

	protected static $_subsetVietnamese = array(
		'vi'
	);

	/**
	 * rtlDirection
	 *
	 * @var array
	 */

	protected static $_rtlDirection = array(
		'ar',
		'fa',
		'he'
	);

	/**
	 * getSubset
	 *
	 * @since 2.1.0
	 */

	public static function getSubset()
	{
		/* cyrillic subset */

		if (in_array(LANGUAGE, self::$_subsetCyrillic))
		{
			$output = 'cyrillic';
		}

		/* vietnamese subset */

		else if (in_array(LANGUAGE, self::$_subsetVietnamese))
		{
			$output = 'vietnamese';
		}

		/* latin subset */

		else
		{
			$output = 'latin';
		}
		return $output;
	}

	/**
	 * getClass
	 *
	 * @since 2.1.0
	 */

	public static function getClass()
	{
		/* collect all classes */

		$classes = array_merge(
			self::_getBrowserClass(),
			self::_getDeviceClass(),
			self::_getDirectionClass(),
			self::_getContentTypeClass()
		);

		/* implode classes */

		$output = implode(' ', array_filter($classes));
		return $output;
	}

	/**
	 * getBrowserClass
	 *
	 * @since 2.1.0
	 */

	protected static function _getBrowserClass()
	{
		$output = array();

		/* browser name and version */

		$output[] = MY_BROWSER . MY_BROWSER_VERSION;

		/* engine name */

		if (MY_ENGINE)
		{
			$output[] = MY_ENGINE;
		}
		return $output;
	}

	/**
	 * getDeviceClass
	 *
	 * @since 2.1.0
	 */

	protected static function _getDeviceClass()
	{
		$output = array();

		/* mobile device */

		if (MY_MOBILE)
		{
			$output[] = 'mobile';
			if (MY_MOBILE !== 'mobile')
			{
				$output[] = MY_MOBILE;
			}
		}

		/* tablet device */

		else if (MY_TABLET)
		{
			$output[] = 'tablet';
			if (MY_TABLET !== 'tablet')
			{
				$output[] = MY_TABLET;
			}
		}

		/* desktop device */

		else if (MY_DESKTOP)
		{
			$output[] = 'desktop';
			$output[] = MY_DESKTOP;
		}
		return $output;
	}

	/**
	 * getDirectionClass
	 *
	 * @since 2.1.0
	 */

	protected static function _getDirectionClass()
	{
		$output = array();

		/* rtl direction */

		if (in_array(LANGUAGE, self::$_rtlDirection))
		{
			$output[] = 'rtl';
		}

		/* ltr direction */

		else
		{
			$output[] = 'ltr';
		}
		return $output;
	}

	/**
	 * getContentTypeClass
	 *
	 * @since 2.1.0
	 */

	protected static function _getContentTypeClass()
	{
		$output = array();

		/* category */

		if (CATEGORY)
		{
			$output[] = 'category';
		}

		/* article */

		else if (ARTICLE)
		{
			$output[] = 'article';
		}
		return $output;
	}
}
?>