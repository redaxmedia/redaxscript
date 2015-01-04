<?php
namespace Redaxscript;

/**
 * parent class to provide template tags
 *
 * @since 2.2.0
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
		ob_start();
		head('base');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
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
		ob_start();
		admin_panel_list();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
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
		ob_start();
		center();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * extra
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function extra($filter = null)
	{
		ob_start();
		extras($filter);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
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
		ob_start();
		head('link');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
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
		ob_start();
		head('meta');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * navigation
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function navigation($type = null, $options = null)
	{
		ob_start();
		navigation_list($type, $options);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * partial
	 *
	 * @since 2.3.0
	 *
	 * @param mixed $file file to include
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
		ob_start();
		foreach ($file as $value)
		{
			include($value);
		}
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
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
		ob_start();
		search();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * script
	 *
	 * @since 2.3.0
	 *
	 * @return string
	 */

	public static function script($mode = null)
	{
		ob_start();
		scripts($mode);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
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
		ob_start();
		styles();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
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
		ob_start();
		head('title');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}
