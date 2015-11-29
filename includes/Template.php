<?php
namespace Redaxscript;

/**
 * parent class to provide template tags
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Template
{
	/**
	 * base
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function base()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('head', array(
			'base'
		));
		// @codeCoverageIgnoreEnd
	}

	/**
	 * admin panel
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function adminPanel()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('admin_panel_list');
		// @codeCoverageIgnoreEnd
	}

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
		return self::_migrate('router', array(
			$options
		));
		// @codeCoverageIgnoreEnd
	}

	/**
	 * extra
	 *
	 * @since 2.3.0
	 *
	 * @param array $options
	 *
	 * @return string
	 */

	public static function extra($options = null)
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('extras', array(
			$options['filter']
		));
		// @codeCoverageIgnoreEnd
	}

	/**
	 * helper class
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function helperClass()
	{
		$helper = new Helper(Registry::getInstance());
		return $helper->getClass();
	}

	/**
	 * helper subset
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function helperSubset()
	{
		$helper = new Helper(Registry::getInstance());
		return $helper->getSubset();
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
	 * link
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function link()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('head', array(
			'link'
		));
		// @codeCoverageIgnoreEnd
	}

	/**
	 * meta
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function meta()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('head', array(
			'meta'
		));
		// @codeCoverageIgnoreEnd
	}

	/**
	 * navigation
	 *
	 * @since 3.0.0
	 *
	 * @param string $type
	 * @param array $options
	 *
	 * @return string
	 */

	public static function navigation($type = null, $options = null)
	{
		// @codeCoverageIgnoreStart
		if ($type === 'languages' || $type === 'templates')
		{
			return self::_migrate($type . '_list', array(
				$options
			));
		}
		return self::_migrate('navigation_list', array(
			$type,
			$options
		));
		// @codeCoverageIgnoreEnd
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
			$file = array(
				$file
			);
		}

		/* include files */

		ob_start();
		foreach ($file as $value)
		{
			include($value);
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
	 * script
	 *
	 * @since 2.3.0
	 *
	 * @param string $mode
	 *
	 * @return string
	 */

	public static function script($mode = null)
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('scripts', array(
			$mode
		));
		// @codeCoverageIgnoreEnd
	}

	/**
	 * search
	 *
	 * @since 3.0.0
	 *
	 * @param string $type
	 *
	 * @return string
	 */

	public static function search($type = 'articles')
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('search', array(
			$type
		));
		// @codeCoverageIgnoreEnd
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
		return Db::getSettings($key);
	}

	/**
	 * style
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function style()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('styles');
		// @codeCoverageIgnoreEnd
	}

	/**
	 * title
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function title()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('head', array(
			'title'
		));
		// @codeCoverageIgnoreEnd
	}

	/**
	 * migrate
	 *
	 * @since 2.3.0
	 *
	 * @param string $function
	 * @param array $parameter
	 *
	 * @return string
	 */

	protected static function _migrate($function = null, $parameter = null)
	{
		// @codeCoverageIgnoreStart
		ob_start();

		/* call with parameter */

		if ($parameter)
		{
			call_user_func_array($function, $parameter);
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
