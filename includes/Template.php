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
		// @codeCoverageIgnoreStart
		return self::_migrate('center');
		// @codeCoverageIgnoreEnd
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
		// @codeCoverageIgnoreStart
		return self::_migrate('extras', array(
			$filter
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
	 * @since 2.3.0
	 *
	 * @param string $table
	 * @param array $options
	 *
	 * @return string
	 */

	public static function navigation($table = null, $options = null)
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('navigation_list', array(
			$table,
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
		// @codeCoverageIgnoreStart
		return self::_migrate('search');
		// @codeCoverageIgnoreEnd
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
