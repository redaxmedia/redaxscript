<?php

/**
 * breadcrumb
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Breadcrumb
 * @author Henry Ruhs, Gary Aylward
 */

class Redaxscript_Breadcrumb
{

	/**
	 * breadcrumbArray
	 *
	 * Array to store all the nodes of the breadcrumb trail
	 *
	 * @var array
	 */
	private static $_breadcrumbArray = array();

	/**
	 * construct
	 *
	 * @since 2.1.0
	 */
	public function __construct()
	{
		if (self::$_breadcrumbArray == array())
		{
			$this->init();
		}
	}

	/**
	 * init
	 *
	 * @since 2.1.0
	 */
	public function init()
	{
		$this->_buildBreadcrumb();
	}

	/**
	 * displayBreadcrumb
	 *
	 * Public function to display the trail as an unordered list
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	public function displayBreadcrumb()
	{
		$preOutput = hook(__FUNCTION__ . '_start');

		$breadcrumbArrayKeys = array_keys(self::$_breadcrumbArray);
		$last = end($breadcrumbArrayKeys);
		$output = '';

		/* collect item output */

		foreach (self::$_breadcrumbArray as $key => $value)
		{
			$title = array_key_exists('title', $value) ? $value['title'] : '';
			$route = array_key_exists('route', $value) ? $value['route'] : '';
			if ($title)
			{
				$output .= '<li>';
				if ($route)
				{
					$output .= anchor_element('internal', '', '', $title, $route);
				}
				else
				{
					$output .= $title;
				}
				$output .= '</li>';
				if ($last != $key)
				{
					$output .= '<li class="divider">' . s('divider') . '</li>';
				}
			}
		}

		/* collect list output */

		if ($output)
		{
			$output = '<ul class="list_breadcrumb">' . $output . '</ul>';
		}
		$output = $preOutput . $output . hook(__FUNCTION__ . '_end');
		return $output;
	}

	/**
	 * _buildBreadcrumb
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	private function _buildBreadcrumb()
	{
		$key = 0;

		/* if title constant */

		if (defined('TITLE') && TITLE)
		{
			self::$_breadcrumbArray[$key]['title'] = TITLE;
		}

		/* else if home */
		else if (FULL_ROUTE == '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('home');
		}

		/* else if administration */
		else if (FIRST_PARAMETER == 'admin')
		{
			self::$_breadcrumbArray[$key]['title'] = l('administration');
			if (ADMIN_PARAMETER)
			{
				self::$_breadcrumbArray[$key]['route'] = 'admin';
			}

			/* join admin title */

			if (l(ADMIN_PARAMETER))
			{
				$key++;
				self::$_breadcrumbArray[$key]['title'] = l(ADMIN_PARAMETER);
				if (ADMIN_PARAMETER != LAST_PARAMETER)
				{
					self::$_breadcrumbArray[$key]['route'] = FULL_ROUTE;
				}

				/* join table title */

				if (l(TABLE_PARAMETER))
				{
					$key++;
					self::$_breadcrumbArray[$key]['title'] = l(TABLE_PARAMETER);
				}
			}
		}

		/* else if default alias */
		else if (check_alias(FIRST_PARAMETER, 1) == 1)
		{
			/* join default title */
			if (l(FIRST_PARAMETER))
			{
				self::$_breadcrumbArray[$key]['title'] = l(FIRST_PARAMETER);
			}
		}

		/* handle error */
		else if (LAST_ID == '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('error');
		}

		/* query title from content */
		else if (defined('FIRST_TABLE') && FIRST_TABLE)
		{
			/* join first title */
			self::$_breadcrumbArray[$key]['title'] = retrieve('title', FIRST_TABLE, 'alias', FIRST_PARAMETER);
			if (FIRST_PARAMETER != LAST_PARAMETER)
			{
				self::$_breadcrumbArray[$key]['route'] = FIRST_PARAMETER;
			}

			/* join second title */

			if (defined('SECOND_TABLE') && SECOND_TABLE)
			{
				$key++;
				self::$_breadcrumbArray[$key]['title'] = retrieve('title', SECOND_TABLE, 'alias', SECOND_PARAMETER);
				if (SECOND_PARAMETER != LAST_PARAMETER)
				{
					self::$_breadcrumbArray[$key]['route'] = FIRST_PARAMETER . '/' . SECOND_PARAMETER;
				}

				/* join third title */

				if (defined('THIRD_TABLE') && THIRD_TABLE)
				{
					$key++;
					self::$_breadcrumbArray[$key]['title'] = retrieve('title', THIRD_TABLE, 'alias', THIRD_PARAMETER);
				}
			}
		}
	}
}
?>