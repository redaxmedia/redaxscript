<?php
namespace Redaxscript\Template;

use Redaxscript\Admin;
use Redaxscript\Db;
use Redaxscript\Config;
use Redaxscript\Console;
use Redaxscript\Breadcrumb;
use Redaxscript\Filesystem;
use Redaxscript\Head;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Navigation;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Router;
use Redaxscript\View;

/**
 * parent class to provide template tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Tag
{
	/**
	 * base
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function base() : string
	{
		$base = new Head\Base(Registry::getInstance());
		return $base->render();
	}

	/**
	 * title
	 *
	 * @since 3.0.0
	 *
	 * @param string $text
	 *
	 * @return string|bool
	 */

	public static function title($text = null)
	{
		$title = new Head\Title(Registry::getInstance());
		return $title->render($text);
	}

	/**
	 * link
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Link
	 */

	public static function link() : Head\Link
	{
		return Head\Link::getInstance();
	}

	/**
	 * meta
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Meta
	 */

	public static function meta() : Head\Meta
	{
		return Head\Meta::getInstance();
	}

	/**
	 * script
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Script
	 */

	public static function script() : Head\Script
	{
		return Head\Script::getInstance();
	}

	/**
	 * style
	 *
	 * @since 3.0.0
	 *
	 * @return Head\Style
	 */

	public static function style() : Head\Style
	{
		return Head\Style::getInstance();
	}

	/**
	 * breadcrumb
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function breadcrumb() : string
	{
		$breadcrumb = new Breadcrumb(Registry::getInstance(), Language::getInstance());
		$breadcrumb->init();
		return $breadcrumb->render();
	}

	/**
	 * console line
	 *
	 * @since 3.0.0
	 *
	 * @return string|bool
	 */

	public static function consoleLine()
	{
		$console = new Console\Console(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$output = $console->init('template');
		if (strlen($output))
		{
			return htmlentities($output);
		}
		return false;
	}

	/**
	 * console form
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function consoleForm() : string
	{
		$consoleForm = new View\ConsoleForm(Registry::getInstance(), Language::getInstance());
		return $consoleForm->render();
	}

	/**
	 * search form
	 *
	 * @since 3.0.0
	 *
	 * @param string $table
	 *
	 * @return string
	 */

	public static function searchForm($table = null) : string
	{
		$searchForm = new View\SearchForm(Registry::getInstance(), Language::getInstance());
		return $searchForm->render($table);
	}

	/**
	 * partial
	 *
	 * @since 3.2.0
	 *
	 * @param string|array $partial
	 *
	 * @return string|null
	 */

	public static function partial($partial = null)
	{
		$output = null;

		/* template filesystem */

		$templateFilesystem = new Filesystem\File();
		$templateFilesystem->init('.');

		/* process partial */

		foreach ((array)$partial as $file)
		{
			$output .= $templateFilesystem->renderFile($file);
		}
		return $output;
	}

	/**
	 * get the registry
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 *
	 * @return string|bool
	 */

	public static function getRegistry($key = null)
	{
		$registry = Registry::getInstance();
		return $registry->get($key);
	}

	/**
	 * get the language
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 * @param string $index
	 *
	 * @return string|bool
	 */

	public static function getLanguage($key = null, $index = null)
	{
		$language = Language::getInstance();
		return $language->get($key, $index);
	}

	/**
	 * get the setting
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 *
	 * @return string|bool
	 */

	public static function getSetting($key = null)
	{
		$settingModel = new Model\Setting();
		return $settingModel->get($key);
	}

	/**
	 * content
	 *
	 * @since 2.3.0
	 *
	 * @return string|null
	 */

	public static function content()
	{
		$adminContent = self::_renderAdminContent();
		return $adminContent ? $adminContent : self::_renderContent();
	}

	/**
	 * render the admin content
	 *
	 * @since 3.3.0
	 *
	 * @return string|null
	 */

	protected function _renderAdminContent()
	{
		$registry = Registry::getInstance();
		if ($registry->get('token') === $registry->get('loggedIn'))
		{
			$adminRouter = new Admin\Router\Router(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
			$adminRouter->init();
			return $adminRouter->routeContent();
		}
	}

	/**
	 * render the content
	 *
	 * @since 2.3.0
	 *
	 * @return string|null
	 */

	protected function _renderContent()
	{
		$router = new Router\Router(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$router->init();
		return $router->routeContent();
	}

	/**
	 * extra
	 *
	 * @since 2.3.0
	 *
	 * @param string $filter
	 *
	 * @return string|null
	 */

	public static function extra($filter = null)
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('extras',
		[
			$filter
		]);
		// @codeCoverageIgnoreEnd
	}

	/**
	 * category raw
	 *
	 * @since 3.0.0
	 *
	 * @return Db
	 */

	public static function categoryRaw()
	{
		return Db::forTablePrefix('categories');
	}

	/**
	 * article raw
	 *
	 * @since 3.0.0
	 *
	 * @return Db
	 */

	public static function articleRaw() : Db
	{
		return Db::forTablePrefix('articles');
	}

	/**
	 * extra raw
	 *
	 * @since 3.0.0
	 *
	 * @return Db
	 */

	public static function extraRaw() : Db
	{
		return Db::forTablePrefix('extras');
	}

	/**
	 * navigation
	 *
	 * @since 3.0.0
	 *
	 * @param string $type
	 * @param array $optionArray
	 *
	 * @return string|null
	 */

	public static function navigation($type = null, array $optionArray = [])
	{
		if ($type == 'articles')
		{
			$navigation = new Navigation\Article(Registry::getInstance(), Language::getInstance());
			$navigation->init($optionArray);
			return $navigation;
		}
		if ($type == 'categories')
		{
			$navigation = new Navigation\Category(Registry::getInstance(), Language::getInstance());
			$navigation->init($optionArray);
			return $navigation;
		}
		if ($type == 'comments')
		{
			$navigation = new Navigation\Comment(Registry::getInstance(), Language::getInstance());
			$navigation->init($optionArray);
			return $navigation;
		}
		if ($type == 'languages')
		{
			$navigation = new Navigation\Language(Registry::getInstance(), Language::getInstance());
			$navigation->init($optionArray);
			return $navigation;
		}
		if ($type == 'templates')
		{
			$navigation = new Navigation\Template(Registry::getInstance(), Language::getInstance());
			$navigation->init($optionArray);
			return $navigation;
		}
	}

	/**
	 * migrate
	 *
	 * @since 2.3.0
	 *
	 * @param string $function
	 * @param array $parameterArray
	 *
	 * @return string|null
	 */

	protected static function _migrate($function = null, $parameterArray = [])
	{
		// @codeCoverageIgnoreStart
		ob_start();

		/* call with parameter */

		if (is_array($parameterArray))
		{
			call_user_func_array($function, $parameterArray);
		}

		/* else simple call */

		else
		{
			call_user_func($function);
		}
		return ob_get_clean();
		// @codeCoverageIgnoreEnd
	}
}
