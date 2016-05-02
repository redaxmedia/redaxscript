<?php
namespace Redaxscript\Template;

use Redaxscript\Registry;

/**
 * helper class to provide template helpers
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Helper
 * @author Henry Ruhs
 * @author Kim Kha Nguyen
 */

class Helper
{
	/**
	 * default subset
	 *
	 * @var string
	 */

	protected static $_subsetDefault = 'latin';

	/**
	 * default direction
	 *
	 * @var string
	 */

	protected static $_directionDefault = 'ltr';

	/**
	 * array of subsets
	 *
	 * @var array
	 */

	protected static $_subsetArray = array(
		'cyrillic' => array(
			'bg',
			'ru'
		),
		'vietnamese' => array(
			'vi'
		)
	);

	/**
	 * array of directions
	 *
	 * @var array
	 */

	protected static $_directionArray = array(
		'rtl' => array(
			'ar',
			'fa',
			'he'
		)
	);

	/**
	 * array of devices
	 *
	 * @var array
	 */

	protected static $_deviceArray = array(
		'mobile' => 'myMobile',
		'tablet' => 'myTablet',
		'desktop' => 'myDesktop'
	);

	/**
	 * prefix for the class
	 *
	 * @var string
	 */

	protected static $_prefix = 'rs-';

	/**
	 * get the subset
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getSubset()
	{
		foreach (self::$_subsetArray as $subset => $language)
		{
			if (in_array(Registry::get('language'), $language))
			{
				return $subset;
			}
		}
		return self::$_subsetDefault;
	}

	/**
	 * get the direction
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getDirection()
	{
		foreach (self::$_directionArray as $direction => $language)
		{
			if (in_array(Registry::get('language'), $language))
			{
				return $direction;
			}
		}
		return self::$_directionDefault;
	}

	/**
	 * get the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getClass()
	{
		$classArray = array_unique(array_merge(
			self::_getBrowserArray(),
			self::_getDeviceArray()
		));
		return self::$_prefix . implode(' ' . self::$_prefix, $classArray);
	}

	/**
	 * get the browser array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected static function _getBrowserArray()
	{
		return array(
			Registry::get('myBrowser'),
			Registry::get('myBrowserVersion'),
			Registry::get('myEngine')
		);
	}

	/**
	 * get the device array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected static function _getDeviceArray()
	{
		foreach (self::$_deviceArray as $system => $value)
		{
			$device = Registry::get($value);
			if ($device)
			{
				return array(
					$system,
					$device
				);
			}
		}
	}
}