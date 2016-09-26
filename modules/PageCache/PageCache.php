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

		/* has directory */

		if (is_dir(self::$_configArray['directory']))
		{
			/* prevent as needed */

			if ($request->getPost() || $registry->get('loggedIn') === $registry->get('token'))
			{
				return false;
			}

			/* handle cache */

			$cache = new Cache();
			$cache->init(self::$_configArray['directory'], self::$_configArray['extension']);
			$fullRoute = $registry->get('fullRoute');

			/* load from cache */

			if ($cache->validate($fullRoute, self::$_configArray['lifetime']))
			{
				return
				[
					'content' => $cache->retrieve($fullRoute)
				];

			}

			/* else store to cache */

			else
			{
				$content = Template\Tag::partial('templates/' . $registry->get('template') . '/index.phtml');
				$cache->store($fullRoute, $content);
				return
				[
					'content' => $content
				];
			}
		}

		/* else handle notification */

		else
		{
			self::setNotification('error', $language->get('directory_not_found') . $language->get('colon') . ' ' . self::$_configArray['directory'] . $language->get('point'));
		}
	}
}
