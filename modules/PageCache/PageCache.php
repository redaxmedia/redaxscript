<?php
namespace Redaxscript\Modules\PageCache;

use Redaxscript\Cache;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Template;

/**
 * simple page cache
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class PageCache extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Page cache',
		'alias' => 'PageCache',
		'author' => 'Redaxmedia',
		'description' => 'Simple page cache',
		'version' => '3.0.0'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public static function adminPanelNotification()
	{
		return self::getNotification();
	}

	/**
	 * renderTemplate
	 *
	 * @since 3.0.0
	 *
	 * @return mixed
	 */

	public static function renderTemplate()
	{
		$registry = Registry::getInstance();
		$request = Request::getInstance();
		$language = Language::getInstance();

		/* handle notification */

		if (!is_dir(self::$_configArray['directory']) && !mkdir(self::$_configArray['directory']))
		{
			self::setNotification('error', $language->get('directory_not_found') . $language->get('colon') . ' ' . self::$_configArray['directory'] . $language->get('point'));
		}

		/* prevent as needed */

		if ($request->getPost() || $registry->get('noCache'))
		{
			return false;
		}

		/* cache as needed */

		$cache = new Cache();
		$cache->init(self::$_configArray['directory'], self::$_configArray['extension']);
		$fullRoute = $registry->get('fullRoute');

		/* load from cache */

		if ($cache->validate($fullRoute, self::$_configArray['lifetime']))
		{
			$raw = $cache->retrieve($fullRoute);
			$content = preg_replace('/' . self::$_configArray['tokenPlaceholder'] . '/', $registry->get('token'), self::_uncompress($raw));
			return
			[
				'header' => function_exists('gzdeflate') ? 'content-encoding: deflate' : null,
				'content' => self::_compress($content)
			];
		}

		/* else store to cache */

		else
		{
			$raw = Template\Tag::partial('templates/' . $registry->get('template') . '/index.phtml');
			$content = preg_replace('/' . $registry->get('token') . '/', self::$_configArray['tokenPlaceholder'], $raw);
			$cache->store($fullRoute, self::_compress($content));
			return
			[
				'content' => $raw
			];
		}
	}

	/**
	 * compress
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 *
	 * @return string
	 */

	public static function _compress($content = null)
	{
		return function_exists('gzdeflate') ? gzdeflate($content) : $content;
	}

	/**
	 * uncompress
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 *
	 * @return string
	 */

	public static function _uncompress($content = null)
	{
		return function_exists('gzinflate') ? gzinflate($content) : $content;
	}
}
