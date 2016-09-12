<?php
namespace Redaxscript\Template;

use Redaxscript\Db;
use Redaxscript\Config;
use Redaxscript\Console;
use Redaxscript\Breadcrumb;
use Redaxscript\Head;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Language;
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
	 * breadcrumb
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function breadcrumb()
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
	 * @return string
	 */

	public static function consoleLine()
	{
		$console = new Console\Console(Registry::getInstance(), Request::getInstance(), Config::getInstance());
		$output = $console->init('template');
		if (is_string($output))
		{
			return htmlentities($output);
		}
	}

	/**
	 * console form
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function consoleForm()
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

	public static function searchForm($table = null)
	{
		$searchForm = new View\SearchForm(Registry::getInstance(), Language::getInstance());
		return $searchForm->render($table);
	}

	/**
	 * partial
	 *
	 * @since 2.3.0
	 *
	 * @param mixed $file
	 *
	 * @return string
	 */

	public static function partial($file = null)
	{
		/* handle file */

		if (is_string($file))
		{
			$file =
			[
				$file
			];
		}

		/* include files */

		ob_start();
		foreach ($file as $value)
		{
			if (file_exists($value))
			{
				include($value);
			}
		}
		return ob_get_clean();
	}

	/**
	 * registry
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 *
	 * @return string
	 */

	public static function registry($key = null)
	{
		$registry = Registry::getInstance();
		return $registry->get($key);
	}

	/**
	 * language
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 * @param string $index
	 *
	 * @return string
	 */

	public static function language($key = null, $index = null)
	{
		$language = Language::getInstance();
		return $language->get($key, $index);
	}

	/**
	 * setting
	 *
	 * @since 2.6.0
	 *
	 * @param string $key
	 *
	 * @return string
	 */

	public static function setting($key = null)
	{
		return Db::getSetting($key);
	}

	/**
	 * content
	 *
	 * @since 2.3.0
	 *
	 * @param array $options
	 *
	 * @return string
	 */

	public static function content($options = null)
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('router',
		[
			$options
		]);
		// @codeCoverageIgnoreEnd
	}

	/**
	 * extra
	 *
	 * @since 2.3.0
	 *
	 * @param array $optionArray
	 *
	 * @return string
	 */

	public static function extra($optionArray = [])
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('extras',
		[
			$optionArray['filter']
		]);
		// @codeCoverageIgnoreEnd
	}

	/**
	 * navigation
	 *
	 * @since 3.0.0
	 *
	 * @param string $type
	 * @param array $optionArray
	 *
	 * @return string
	 */

	public static function navigation($type = null, $optionArray = [])
	{
		// @codeCoverageIgnoreStart
		if ($type === 'languages' || $type === 'templates')
		{
			return self::_migrate($type . '_list',
			[
				$optionArray
			]);
		}
		return self::_migrate('navigation_list',
		[
			$type,
			$optionArray
		]);
		// @codeCoverageIgnoreEnd
	}

	/**
	 * base
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function base()
	{
		$base = new Head\Base(Registry::getInstance());
		return $base->render();
	}

	/**
	 * title
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function title()
	{
		$title = new Head\Title(Registry::getInstance());
		return $title->render();
	}

	/**
	 * link
	 *
	 * @since 3.0.0
	 *
	 * @return mixed
	 */

	public static function link()
	{
		return Head\Link::getInstance();
	}

	/**
	 * meta
	 *
	 * @since 3.0.0
	 *
	 * @return mixed
	 */

	public static function meta()
	{
		return Head\Meta::getInstance();
	}

	/**
	 * style
	 *
	 * @since 3.0.0
	 *
	 * @return mixed
	 */

	public static function style()
	{
		//return Head\Style::getInstance();
	}

	/**
	 * script
	 *
	 * @since 3.0.0
	 *
	 * @return mixed
	 */

	public static function script()
	{
		return Head\Script::getInstance();
	}

	/**
	 * migrate
	 *
	 * @since 2.3.0
	 *
	 * @param string $function
	 * @param array $parameterArray
	 *
	 * @return string
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
