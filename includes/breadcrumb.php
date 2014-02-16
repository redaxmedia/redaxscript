<?php

/**
 * Redaxscript Breadcrumb
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Breadcrumb
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Redaxscript_Breadcrumb
{
	/**
	 * registry
	 *
	 * instance of the registry class
	 *
	 * @var object
	 */

	private $_registry;

	/**
	 * breadcrumbArray
	 *
	 * array to store all nodes of the breadcrumb
	 *
	 * @var array
	 */

	protected static $_breadcrumbArray = array();

	/**
	 * classes
	 *
	 * array of classes used to style breadcrumb
	 *
	 * @var array
	 */

	protected $_classes = array(
		'list' => 'list_breadcrumb',
		'divider' => 'divider'
	);

	/**
	 * construct
	 *
	 * @since 2.1.0
	 * 
	 * @param object $registry
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
		$this->_build();
	}

	/**
	 * get
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function get()
	{
		return self::$_breadcrumbArray;
	}

	/**
	 * render
	 *
	 * render breadcrumb array
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = hook(__FUNCTION__ . '_start');

		/* breadcrumb keys */

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

				/* build link if route */

				if ($route)
				{
					$output .= anchor_element('internal', '', '', $title, $route);
				}

				/* else plain text */

				else
				{
					$output .= $title;
				}
				$output .= '</li>';

				/* add divider */

				if ($last !== $key)
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
	 * build
	 *
	 * build breadcrumb array
	 *
	 * @since 2.1.0
	 */

	private function _build()
	{
		$key = 0;
		self::$_breadcrumbArray = array();

		/* if title constant */

		if ($this->_registry->get('title'))
		{
			self::$_breadcrumbArray[$key]['title'] = $this->_registry->get('title');
		}

		/* else if home */

		else if ($this->_registry->get('fullRoute') === '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('home');
		}

		/* else if administration */

		else if ($this->_registry->get('firstParameter') === 'admin')
		{
			$this->_buildAdmin($key);
		}

		/* else if default alias */

		else if (check_alias($this->_registry->get('firstParameter'), 1) === 1)
		{
			/* join default title */

			if (l($this->_registry->get('firstParameter')))
			{
				self::$_breadcrumbArray[$key]['title'] = l($this->_registry->get('firstParameter'));
			}
		}

		/* handle error */

		else if ($this->_registry->get('lastId') === '')
		{
			self::$_breadcrumbArray[$key]['title'] = l('error');
		}

		/* query title from content */

		else if ($this->_registry->get('firstTable'))
		{
			$this->_buildContent($key);
		}
	}

	/**
	 * buildAdmin
	 *
	 * build admin breadcrumb array
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key
	 */

	private function _buildAdmin($key = null)
	{
		self::$_breadcrumbArray[$key]['title'] = l('administration');

		/* if admin parameter  */

		if ($this->_registry->get('adminParameter'))
		{
			self::$_breadcrumbArray[$key]['route'] = 'admin';
		}

		/* join admin title */

		if (l($this->_registry->get('adminParameter')))
		{
			$key++;
			self::$_breadcrumbArray[$key]['title'] = l($this->_registry->get('adminParameter'));

			/* set route if not end */

			if ($this->_registry->get('adminParameter') !== $this->_registry->get('lastParameter'))
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

	/**
	 * buildContent
	 *
	 * build content breadcrumb array
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key
	 */

	private function _buildContent($key = null)
	{
		/* join first title */

		self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->_registry->get('firstTable'), 'alias', $this->_registry->get('firstParameter'));

		/* set route if not end */

		if ($this->_registry->get('firstParameter') !== $this->_registry->get('lastParameter'))
		{
			self::$_breadcrumbArray[$key]['route'] = $this->_registry->get('firstParameter');
		}

		/* join second title */

		if ($this->_registry->get('secondTable'))
		{
			$key++;
			self::$_breadcrumbArray[$key]['title'] = retrieve('title', $this->_registry->get('secondTable'), 'alias', $this->_registry->get('secondParameter'));

			/* set route if not end */

			if ($this->_registry->get('secondParameter') !== $this->_registry->get('lastParameter'))
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
?>