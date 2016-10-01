<?php
namespace Redaxscript\Template;

use Redaxscript\Assetic;
use Redaxscript\Db;
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
	 * robot
	 *
	 * @var string
	 */

	protected static $_robot = 'all';

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
	 * array of robots
	 *
	 * @var array
	 */

	protected static $_robotArray =
	[
		'none',
		'all',
		'index',
		'follow',
		'index_no',
		'follow_no'
	];

	/**
	 * array of subsets
	 *
	 * @var array
	 */

	protected static $_subsetArray =
	[
		'cyrillic' =>
		[
			'bg',
			'ru'
		],
		'vietnamese' =>
		[
			'vi'
		]
	];

	/**
	 * array of directions
	 *
	 * @var array
	 */

	protected static $_directionArray =
	[
		'rtl' =>
		[
			'ar',
			'fa',
			'he'
		]
	];

	/**
	 * array of devices
	 *
	 * @var array
	 */

	protected static $_deviceArray =
	[
		'mobile' => 'myMobile',
		'tablet' => 'myTablet',
		'desktop' => 'myDesktop'
	];

	/**
	 * get the canonical
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getCanonical()
	{
		$firstTable = Registry::get('firstTable');
		$secondTable = Registry::get('secondTable');
		$lastTable = Registry::get('lastTable');
		$firstParameter = Registry::get('firstParameter');
		$secondParameter = Registry::get('secondParameter');
		$categoryParameter = $secondTable === 'categories' ? $secondParameter : $firstParameter;
		$fullRoute = Registry::get('fullRoute');

		/* find route */

		if ($firstTable === 'categories' && $lastTable === 'articles')
		{
			$categoryId = Db::forTablePrefix($firstTable)->where('alias', $categoryParameter)->findOne()->id;
			$articlesTotal = Db::forTablePrefix('articles')->where('category', $categoryId)->count();
			if ($articlesTotal === 1)
			{
				$route = $firstParameter;
				if ($secondTable === 'categories')
				{
					$route .= '/' . $secondParameter;
				}
			}
		}

		/* handle route  */

		if ($route)
		{
			return Registry::get('parameterRoute') . $route;
		}
		return $fullRoute;
	}

	/**
	 * get the description
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getDescription()
	{
		$lastTable = Registry::get('lastTable');
		$lastId = Registry::get('lastId');
		$metaDescription = Registry::get('metaDescription');

		/* find description */

		if ($metaDescription)
		{
			$description = $metaDescription;
		}
		else if ($lastTable && $lastId)
		{
			$lastContent = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$description = $lastContent->description;
		}

		/* handle description */

		if ($description)
		{
			return $description;
		}
		return Db::getSetting('description');
	}

	/**
	 * get the keywords
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getKeywords()
	{
		$lastTable = Registry::get('lastTable');
		$lastId = Registry::get('lastId');
		$metaKeywords = Registry::get('metaKeywords');

		/* find keywords */

		if ($metaKeywords)
		{
			$keywords = $metaKeywords;
		}
		else if ($lastTable && $lastId)
		{
			$lastContent = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$keywords = $lastContent->keywords;
		}

		/* handle keywords */

		if ($keywords)
		{
			return $keywords;
		}
		return Db::getSetting('keywords');
	}

	/**
	 * get the keywords
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getRobots()
	{
		$lastTable = Registry::get('lastTable');
		$lastId = Registry::get('lastId');
		$contentError = Registry::get('contentError');
		$metaRobots = Registry::get('metaRobots');

		/* find robots */

		if ($metaRobots)
		{
			$robots = $metaRobots;
		}
		else if ($contentError)
		{
			$robots = 0;
		}
		else if ($lastTable && $lastId)
		{
			$lastContent = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->whereNull('access')->findOne();
			$robots = $lastContent->robots;
		}

		/* handle robots */

		if (array_key_exists($robots, self::$_robotArray))
		{
			return self::$_robotArray[$robots];
		}
		return self::$_robot;
	}

	/**
	 * get the transport
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getTransport()
	{
		return Assetic\Transport::scriptsTransport();
	}

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
		return
		[
			Registry::get('myBrowser'),
			Registry::get('myBrowserVersion'),
			Registry::get('myEngine')
		];
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
				return
				[
					$system,
					$device
				];
			}
		}
	}
}
