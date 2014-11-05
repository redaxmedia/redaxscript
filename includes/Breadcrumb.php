<?php
namespace Redaxscript;

use Redaxscript\Validator;

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

class Breadcrumb
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
	 * array to store the nodes of the breadcrumb
	 *
	 * @var array
	 */

	protected $_breadcrumbArray = array();

	/**
	 * options of the breadcrumb
	 *
	 * @var array
	 */

	protected $_options = array(
		'className' => array(
			'list' => 'list-breadcrumb',
			'divider' => 'item-divider'
		)
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.1.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param array $options options of the breadcrumb
	 */

	public function __construct(Registry $registry, Language $language, $options = null)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		if (is_array($options))
		{
			$this->_options = array_unique(array_merge($this->_options, $options));
		}
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
	 * get the breadcrumb array for further processing
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function getArray()
	{
		return $this->_breadcrumbArray;
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
		$output = Hook::trigger('breadcrumb_start');

		/* breadcrumb keys */

		$breadcrumbKeys = array_keys($this->_breadcrumbArray);
		$last = end($breadcrumbKeys);

		/* html elements */

		$linkElement = new Element('a');
		$itemElement = new Element('li');
		$listElement = new Element('ul');

		/* collect item output */

		foreach ($this->_breadcrumbArray as $key => $value)
		{
			$title = array_key_exists('title', $value) ? $value['title'] : null;
			$route = array_key_exists('route', $value) ? $value['route'] : null;
			if ($title)
			{
				$output .= '<li>';

				/* build link if route */

				if ($route)
				{
					$output .= $linkElement->attr(array(
						'href' => $this->_registry->get('rewriteRoute') . $route,
						'title' => $title
					))->text($title);
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
					$output .= $itemElement->attr('class', $this->_options['className']['divider'])->text(Db::getSettings('divider'));
				}
			}
		}

		/* collect list output */

		if ($output)
		{
			$output = $listElement->attr('class', $this->_options['className']['list'])->html($output);
		}
		$output .= Hook::trigger('breadcrumb_end');
		return $output;
	}

	/**
	 * build the breadcrumb array
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key key of the item
	 */

	protected function _build($key = 0)
	{
		$aliasValidator = new Validator\Alias();
		$title = $this->_registry->get('title');
		$firstParameter = $this->_registry->get('firstParameter');
		$firstTable = $this->_registry->get('firstTable');
		$fullRoute = $this->_registry->get('fullRoute');
		$lastId = $this->_registry->get('lastId');

		/* if title */

		if ($title)
		{
			$this->_breadcrumbArray[$key]['title'] = $title;
		}

		/* else if home */

		else if (!$fullRoute)
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('home');
		}

		/* else if administration */

		else if ($firstParameter === 'admin')
		{
			$this->_buildAdmin($key);
		}

		/* else if default alias */

		else if ($aliasValidator->validate($firstParameter, Validator\Alias::MODE_DEFAULT) === Validator\Validator::PASSED)
		{
			/* join default title */

			if ($firstParameter && $this->_language->get($firstParameter))
			{
				$this->_breadcrumbArray[$key]['title'] = $this->_language->get($firstParameter);
			}
		}

		/* handle error */

		else if (!$lastId)
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('error');
		}

		/* query title from content */

		else if ($firstTable)
		{
			$this->_buildContent($key);
		}
	}

	/**
	 * build the breadcrumb array for current administration
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key key of the item
	 */

	protected function _buildAdmin($key = 0)
	{
		$adminParameter = $this->_registry->get('adminParameter');
		$tableParameter = $this->_registry->get('tableParameter');
		$lastParameter = $this->_registry->get('lastParameter');
		$fullRoute = $this->_registry->get('fullRoute');

		/* join first title */

		$this->_breadcrumbArray[$key]['title'] = $this->_language->get('administration');

		/* if admin parameter  */

		if ($adminParameter)
		{
			$this->_breadcrumbArray[$key]['route'] = 'admin';
		}

		/* join admin title */

		if ($adminParameter && $this->_language->get($adminParameter))
		{
			$key++;
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get($adminParameter);

			/* set route if not end */

			if ($adminParameter !== $lastParameter)
			{
				$this->_breadcrumbArray[$key]['route'] = $fullRoute;
			}

			/* join table title */

			if ($tableParameter && $this->_language->get($tableParameter))
			{
				$key++;
				$this->_breadcrumbArray[$key]['title'] = $this->_language->get($tableParameter);
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

	protected function _buildContent($key = 0)
	{
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$thirdParameter = $this->_registry->get('thirdParameter');
		$lastParameter = $this->_registry->get('lastParameter');
		$firstTable = $this->_registry->get('firstTable');
		$secondTable = $this->_registry->get('secondTable');
		$thirdTable = $this->_registry->get('thirdTable');

		/* join first title */

		$this->_breadcrumbArray[$key]['title'] = Db::forTablePrefix($firstTable)->where('alias', $firstParameter)->findOne()->title;

		/* set route if not end */

		if ($firstParameter !== $lastParameter)
		{
			$this->_breadcrumbArray[$key]['route'] = $firstParameter;
		}

		/* join second title */

		if ($secondTable)
		{
			$key++;
			$this->_breadcrumbArray[$key]['title'] = Db::forTablePrefix($secondTable)->where('alias', $secondParameter)->findOne()->title;

			/* set route if not end */

			if ($secondParameter !== $lastParameter)
			{
				$this->_breadcrumbArray[$key]['route'] = $firstParameter . '/' . $secondParameter;
			}

			/* join third title */

			if ($thirdTable)
			{
				$key++;
				$this->_breadcrumbArray[$key]['title'] = Db::forTablePrefix($thirdTable)->where('alias', $thirdParameter)->findOne()->title;
			}
		}
	}
}