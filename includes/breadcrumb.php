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
	private $C;
	protected $classes = array();


	/**
	 * construct
	 *
	 * @since 2.1.0
	 */

	public function __construct(Redaxscript_Constants $constants)
	{
		$this->C = $constants;
		$this->classes['list'] = 'list_breadcrumb';
		$this->classes['divider'] = 'divider';
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
					$output .= '<li class="' . $this->classes['divider'] . '">' . s('divider') . '</li>';
				}
			}
		}

		/* collect list output */

		if ($output)
		{
			$output = '<ul class="' . $this->classes['list'] . '">' . $output . '</ul>';
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

		if ($this->C['title'])
		{
			self::$_breadcrumbArray[$key]['title'] = $this->C['title'];
		}

		/* else if home */

		else if ($this->C['fullRoute'] == '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('home');
		}

		/* else if administration */

		else if ($this->C['firstParameter'] == 'admin')
		{
			self::$_breadcrumbArray[$key]['title'] = l('administration');

			if ($this->C['adminParameter'])
			{
				self::$_breadcrumbArray[$key]['route'] = 'admin';
			}

			/* join admin title */

			if (l($this->C['adminParameter']))
			{
				$key++;
				self::$_breadcrumbArray[$key]['title'] = l($this->C['adminParameter']);

				if ($this->C['adminParameter'] != $this->C['lastParameter'])
				{
					self::$_breadcrumbArray[$key]['route'] = $this->C['fullRoute'];
				}

				/* join table title */

				if (l($this->C['tableParameter']))
				{
					$key++;
					self::$_breadcrumbArray[$key]['title'] = l($this->C['tableParameter']);
				}
			}
		}

		/* else if default alias */

		else if (check_alias($this->C['firstParameter'], 1) == 1)
		{
			/* join default title */

			if (l($this->C['firstParameter']))
			{
				self::$_breadcrumbArray[$key]['title'] = l($this->C['firstParameter']);
			}
		}

		/* handle error */

		else if ($this->C['lastId'] == '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('error');
		}

		/* query title from content */

		else if ($this->C['firstTable'])
		{
			/* join first title */
			self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->C['firstTable'], 'alias', $this->C['firstParameter']);

			if ($this->C['firstParameter'] != $this->C['lastParameter'])
			{
				self::$_breadcrumbArray[$key]['route'] = $this->C['firstParameter'];
			}

			/* join second title */

			if ($this->C['secondTable'])
			{
				$key++;
				self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->C['secondTable'], 'alias', $this->C['secondParameter']);

				if ($this->C['secondParameter'] != $this->C['lastParameter'])
				{
					self::$_breadcrumbArray[$key]['route'] = $this->C['firstParameter'] . '/' . $this->C['secondParameter'];
				}

				/* join third title */

				if ($this->C['thirdTable'])
				{
					$key++;
					self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->C['thirdTable'], 'alias', $this->C['thirdParameter']);
				}
			}
		}
	}
}
?>