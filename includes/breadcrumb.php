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
	private $_registry;
	protected $_classes = array('list' => 'list_breadcrumb', 'divider' => 'divider');

	/**
	 * construct
	 *
	 * @since 2.1.0
	 */

	public function __construct(Redaxscript_Registry $registry)
	{
		$this->_registry = $registry;
		$this->init();
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
		$output = hook(__FUNCTION__ . '_start');

		$breadcrumbKeys = array_keys(self::$_breadcrumbArray);
		$last = end($breadcrumbKeys);

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
					$output .= '<li class="' . $this->_classes['divider'] . '">' . s('divider') . '</li>';
				}
			}
		}

		/* collect list output */

		if ($output)
		{
			$output = '<ul class="' . $this->_classes['list'] . '">' . $output . '</ul>';
		}
		$output .= hook(__FUNCTION__ . '_end');
		return $output;
	}

	/**
	 * getArray
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	public function getArray()
	{
		return self::$_breadcrumbArray;
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
		self::$_breadcrumbArray = array();
		$key = 0;

		/* if title constant */

		if ($this->_registry->get('title'))
		{
			self::$_breadcrumbArray[$key]['title'] = $this->_registry->get('title');
		}

		/* else if home */

		else if ($this->_registry->get('fullRoute') == '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('home');
		}

		/* else if administration */

		else if ($this->_registry->get('firstParameter') == 'admin')
		{
			self::$_breadcrumbArray[$key]['title'] = l('administration');

			if ($this->_registry->get('adminParameter'))
			{
				self::$_breadcrumbArray[$key]['route'] = 'admin';
			}

			/* join admin title */

			if (l($this->_registry->get('adminParameter')))
			{
				$key++;
				self::$_breadcrumbArray[$key]['title'] = l($this->_registry->get('adminParameter'));

				if ($this->_registry->get('adminParameter') != $this->_registry->get('lastParameter'))
				{
					self::$_breadcrumbArray[$key]['route'] = $this->_registry->get('fullRoute');
				}

				/* join table title */

				if (l($this->_registry->get('tableParameter')))
				{
					$key++;
					self::$_breadcrumbArray[$key]['title'] = l($this->_registry->get('tableParameter'));
				}
			}
		}

		/* else if default alias */

		else if (check_alias($this->_registry->get('firstParameter'), 1) == 1)
		{
			/* join default title */

			if (l($this->_registry->get('firstParameter')))
			{
				self::$_breadcrumbArray[$key]['title'] = l($this->_registry->get('firstParameter'));
			}
		}

		/* handle error */

		else if ($this->_registry->get('lastId') == '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('error');
		}

		/* query title from content */

		else if ($this->_registry->get('firstTable'))
		{
			/* join first title */
			self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->_registry->get('firstTable'), 'alias', $this->_registry->get('firstParameter'));

			if ($this->_registry->get('firstParameter') != $this->_registry->get('lastParameter'))
			{
				self::$_breadcrumbArray[$key]['route'] = $this->_registry->get('firstParameter');
			}

			/* join second title */

			if ($this->_registry->get('secondTable'))
			{
				$key++;
				self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->_registry->get('secondTable'), 'alias', $this->_registry->get('secondParameter'));

				if ($this->_registry->get('secondParameter') != $this->_registry->get('lastParameter'))
				{
					self::$_breadcrumbArray[$key]['route'] = $this->_registry->get('firstParameter') . '/' . $this->_registry->get('secondParameter');
				}

				/* join third title */

				if ($this->_registry->get('thirdTable'))
				{
					$key++;
					self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->_registry->get('thirdTable'), 'alias', $this->_registry->get('thirdParameter'));
				}
			}
		}
	}
}
?>