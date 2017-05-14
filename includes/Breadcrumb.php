<?php
namespace Redaxscript;

/**
 * parent class to create a breadcrumb navigation
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
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * array of the breadcrumb
	 *
	 * @var array
	 */

	protected $_breadcrumbArray = [];

	/**
	 * options of the breadcrumb
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-breadcrumb',
			'divider' => 'rs-item-divider'
		],
		'divider' => null
	];

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * stringify the breadcrumb
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->render();
	}

	/**
	 * init the class
	 *
	 * @since 2.6.0
	 *
	 * @param array $optionArray options of the breadcrumb
	 */

	public function init($optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}
		if (!$this->_optionArray['divider'])
		{
			$this->_optionArray['divider'] = Db::getSetting('divider') ? Db::getSetting('divider') : null;
		}
		$this->_create();
	}

	/**
	 * get the breadcrumb array
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
		$output = Module\Hook::trigger('breadcrumbStart');

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $this->_optionArray['className']['list']
		]);
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$linkElement = new Html\Element();
		$linkElement->init('a');

		/* breadcrumb keys */

		$breadcrumbKeys = array_keys($this->_breadcrumbArray);
		$lastKey = end($breadcrumbKeys);

		/* process breadcrumb */

		foreach ($this->_breadcrumbArray as $key => $value)
		{
			$title = array_key_exists('title', $value) ? $value['title'] : null;
			$route = array_key_exists('route', $value) ? $value['route'] : null;
			if ($title)
			{
				$itemElement->clear();

				/* append link */

				if ($route)
				{
					$itemElement->append(
						$linkElement
							->attr('href', $this->_registry->get('parameterRoute') . $route)
							->text($title)
					);
				}

				/* else append text */

				else
				{
					$itemElement->text($title);
				}
				$listElement->append($itemElement);

				/* append divider */

				if ($key !== $lastKey && $this->_optionArray['divider'])
				{
					$listElement->append(
						$itemElement
							->copy()
							->addClass($this->_optionArray['className']['divider'])
							->text($this->_optionArray['divider'])
					);
				}
			}
		}

		/* collect list output */

		$output .= $listElement;
		$output .= Module\Hook::trigger('breadcrumbEnd');
		return $output;
	}

	/**
	 * create the breadcrumb array
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key key of the item
	 */

	protected function _create($key = 0)
	{
		$aliasValidator = new Validator\Alias();
		$title = $this->_registry->get('useTitle');
		$firstParameter = $this->_registry->get('firstParameter');
		$firstTable = $this->_registry->get('firstTable');
		$fullRoute = $this->_registry->get('fullRoute');
		$lastId = $this->_registry->get('lastId');

		/* title */

		if ($title)
		{
			$this->_breadcrumbArray[$key]['title'] = $title;
		}

		/* else home */

		else if (!$fullRoute)
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('home');
		}

		/* else administration */

		else if ($firstParameter === 'admin')
		{
			$this->_createAdmin($key);
		}

		/* else default alias */

		else if ($aliasValidator->validate($firstParameter, Validator\Alias::MODE_DEFAULT) === Validator\ValidatorInterface::PASSED && $firstParameter && $this->_language->get($firstParameter))
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get($firstParameter);
		}

		/* handle error */

		else if (!$lastId)
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('error');
		}

		/* query title from content */

		else if ($firstTable)
		{
			$this->_createContent($key);
		}
	}

	/**
	 * create the breadcrumb array for the administration
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key key of the item
	 */

	protected function _createAdmin($key = 0)
	{
		$adminParameter = $this->_registry->get('adminParameter');
		$tableParameter = $this->_registry->get('tableParameter');
		$lastParameter = $this->_registry->get('lastParameter');
		$fullRoute = $this->_registry->get('fullRoute');

		/* join first title */

		$this->_breadcrumbArray[$key]['title'] = $this->_language->get('administration');

		/* admin parameter */

		if ($adminParameter)
		{
			$this->_breadcrumbArray[$key]['route'] = 'admin';
		}

		/* join admin title */

		if ($adminParameter && $this->_language->get($adminParameter))
		{
			$key++;
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get($adminParameter);

			/* set route */

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
	 * create the breadcrumb array for the content
	 *
	 * @since 2.1.0
	 *
	 * @param integer $key
	 */

	protected function _createContent($key = 0)
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

		/* set route */

		if ($firstParameter !== $lastParameter)
		{
			$this->_breadcrumbArray[$key]['route'] = $firstParameter;
		}

		/* join second title */

		if ($secondTable)
		{
			$key++;
			$this->_breadcrumbArray[$key]['title'] = Db::forTablePrefix($secondTable)->where('alias', $secondParameter)->findOne()->title;

			/* set route */

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
