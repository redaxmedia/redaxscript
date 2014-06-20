<?php

/**
 * parent class to provide a location based breadcrumb navigation
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
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * array to store all the nodes of the breadcrumb
	 *
	 * @var array
	 */

	protected static $_breadcrumbArray = array();

	/**
	 * array of classes used to style the breadcrumb
	 *
	 * @var array
	 */

	protected $_classes = array(
		'list' => 'list_breadcrumb',
		'divider' => 'divider'
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.1.0
	 *
	 * @param Redaxscript_Registry $registry instance of the registry class
	 * @param Redaxscript_Language $language instance of the language class
	 */

	public function __construct(Redaxscript_Registry $registry, Redaxscript_Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.1.0
	 */

	public function init()
	{
		$this->_build();
	}

	/**
	 * return the raw breadcrumb array for further processing
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
	 * render the breadcrumb trail as an unordered list
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
	 * build the breadcrumb array
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

		else if (!$this->_registry->get('fullRoute'))
		{
			self::$_breadcrumbArray[$key]['title'] = $this->_language->get('home');
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

			if ($this->_language->get($this->_registry->get('firstParameter')))
			{
				self::$_breadcrumbArray[$key]['title'] = $this->_language->get($this->_registry->get('firstParameter'));
			}
		}

		/* handle error */

		else if (!$this->_registry->get('lastId'))
		{
			self::$_breadcrumbArray[$key]['title'] = $this->_language->get('error');
		}

		/* query title from content */

		else if ($this->_registry->get('firstTable'))
		{
			$this->_buildContent($key);
		}
	}

	/**
	 * build the breadcrumb array for current administration
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key
	 */

	private function _buildAdmin($key = null)
	{
		self::$_breadcrumbArray[$key]['title'] = $this->_language->get('administration');

		/* if admin parameter  */

		if ($this->_registry->get('adminParameter'))
		{
			self::$_breadcrumbArray[$key]['route'] = 'admin';
		}

		/* join admin title */

		if ($this->_language->get($this->_registry->get('adminParameter')))
		{
			$key++;
			self::$_breadcrumbArray[$key]['title'] = $this->_language->get($this->_registry->get('adminParameter'));

			/* set route if not end */

			if ($this->_registry->get('adminParameter') !== $this->_registry->get('lastParameter'))
			{
				self::$_breadcrumbArray[$key]['route'] = $this->_registry->get('fullRoute');
			}

			/* join table title */

			if ($this->_language->get($this->_registry->get('tableParameter')))
			{
				$key++;
				self::$_breadcrumbArray[$key]['title'] = $this->_language->get($this->_registry->get('tableParameter'));
			}
		}
	}

	/**
	 * build the breadcrumb array for current content
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