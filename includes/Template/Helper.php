<?php
namespace Redaxscript\Template;

use Redaxscript\Asset;
use Redaxscript\Db;
use Redaxscript\Registry;
use Redaxscript\Language;

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
	 * get the title
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getTitle()
	{
		$lastTable = Registry::get('lastTable');
		$lastId = Registry::get('lastId');
		$useTitle = Registry::get('useTitle');

		/* find title */

		if ($useTitle)
		{
			$title = $useTitle;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$title = $content->title;
			if ($title)
			{
				$title .= Db::getSetting('divider') . Db::getSetting('title');
			}
		}

		/* handle description */

		if ($title)
		{
			return $title;
		}
		return Db::getSetting('title');
	}

	/**
	 * get the canonical
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function getCanonical()
	{
		$lastTable = Registry::get('lastTable');
		$lastId = Registry::get('lastId');
		$canonicalUrl = Registry::get('root');

		/* find route */

		if ($lastTable === 'categories')
		{
			$articles = Db::forTablePrefix('articles')->where('category', $lastId);
			$articlesTotal = $articles->findMany()->count();
			if ($articlesTotal === 1)
			{
				$lastTable = 'articles';
				$lastId = $articles->findOne()->id;
			}
		}
		$canonicalRoute = build_route($lastTable, $lastId);

		/* handle route */

		if ($canonicalRoute)
		{
			return $canonicalUrl . '/' . Registry::get('parameterRoute') . $canonicalRoute;
		}
		return $canonicalUrl;
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
		$useDescription = Registry::get('useDescription');

		/* find description */

		if ($useDescription)
		{
			$description = $useDescription;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$description = $content->description;

			/* handle parent */

			if (!$description)
			{
				$parentId = $content->category ? $content->category : $content->parent;
				$parent = Db::forTablePrefix('categories')->whereIdIs($parentId)->whereNull('access')->findOne();
				$description = $parent->description;
			}
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
		$useKeywords = Registry::get('useKeywords');

		/* find keywords */

		if ($useKeywords)
		{
			$keywords = $useKeywords;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne();
			$keywords = $content->keywords;

			/* handle parent */

			if (!$keywords)
			{
				$parentId = $content->category ? $content->category : $content->parent;
				$parent = Db::forTablePrefix('categories')->whereIdIs($parentId)->whereNull('access')->findOne();
				$keywords = $parent->keywords;
			}
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
		$useRobots = Registry::get('useRobots');

		/* find robots */

		if ($useRobots)
		{
			$robots = $useRobots;
		}
		else if ($contentError)
		{
			$robots = 0;
		}
		else if ($lastTable && $lastId)
		{
			$content = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->whereNull('access')->findOne();
			$robots = $content->robots;

			/* handle parent */

			if (!$robots)
			{
				$parentId = $content->category ? $content->category : $content->parent;
				$parent = Db::forTablePrefix('categories')->whereIdIs($parentId)->whereNull('access')->findOne();
				$robots = $parent->robots;
			}
		}

		/* handle robots */

		if (array_key_exists($robots, self::$_robotArray))
		{
			return self::$_robotArray[$robots];
		}
		$robots = Db::getSetting('robots');
		return self::$_robotArray[$robots];
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
		$transport = new Asset\Transport(Registry::getInstance(), Language::getInstance());
		return $transport->getArray();
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
		$language = Registry::get('language');

		/* process subset */

		foreach (self::$_subsetArray as $subset => $valueArray)
		{
			if (in_array($language, $valueArray))
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
		$language = Registry::get('language');

		/* process direction */

		foreach (self::$_directionArray as $direction => $valueArray)
		{
			if (in_array($language, $valueArray))
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
