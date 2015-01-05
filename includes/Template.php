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
		return self::_migrate('head', array(
			'base'
		));
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
		return self::_migrate('admin_panel_list');
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
		$options = array(
			'className' => array(
				'list' => 'list_breadcrumb',
				'divider' => 'divider'
			)
		);
		$breadcrumb = new Breadcrumb(Registry::getInstance(), Language::getInstance(), $options);
		return $breadcrumb->render();
	}

	/**
	 * content
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function content()
	{
		return self::_migrate('center');
	}

	/**
	 * extra
	 *
	 * @since 2.3.0
	 *
	 * @param mixed $filter
	 *
	 * @return string
	 */

	public static function extra($filter = null)
	{
		return self::_migrate('extras', array(
			$filter
		));
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
	 * link
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function link()
	{
		return self::_migrate('head', array(
			'link'
		));
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
		return self::_migrate('head', array(
			'meta'
		));
	}

	/**
	 * navigation
	 *
	 * @since 2.3.0
	 *
	 * @param string $table
	 * @param array $options
	 *
	 * @return string
	 */

	public static function navigation($table = null, $options = null)
	{
		return self::_migrate('navigation_list', array(
			$table,
			$options
		));
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
		if (!is_array($file))
		{
			$file = array(
				$file
			);
		}

		/* include as needed */

		ob_start();
		foreach ($file as $value)
		{
			include($value);
		}
		return ob_get_clean();
	}

	/**
	 * search
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function search()
	{
		return self::_migrate('search');
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
		return self::_migrate('scripts', array(
			$mode
		));
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
		return self::_migrate('styles');
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
		return self::_migrate('head', array(
			'title'
		));
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
	}
}
