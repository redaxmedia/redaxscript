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
	 * prefix for the class
	 *
	 * @var string
	 */

	protected static $_prefix = 'rs-';

	/**
	 * subset
	 *
	 * @var string
	 */

	protected static $_subset = 'latin';

	/**
	 * direction
	 *
	 * @var string
	 */

	protected static $_direction = 'ltr';

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
		return self::$_subset;
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
		return self::$_direction;
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

		/* process class */

		foreach ($classArray as $key => $value)
		{
			$classArray[$key] = self::$_prefix . 'is-' . $value;
		}
		return implode(' ', $classArray);
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
