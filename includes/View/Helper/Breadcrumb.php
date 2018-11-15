<?php
namespace Redaxscript\View\Helper;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\View\ViewAbstract;
use function array_key_exists;
use function array_keys;
use function array_replace_recursive;
use function end;
use function is_array;

/**
 * helper class to create a breadcrumb navigation
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Breadcrumb extends ViewAbstract
{
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
	 * stringify the breadcrumb
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function __toString() : string
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

	public function init(array $optionArray = [])
	{
		$settingModel = new Model\Setting();
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		if (!$this->_optionArray['divider'])
		{
			$this->_optionArray['divider'] = $settingModel->get('divider');
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

	public function getArray() : array
	{
		return $this->_breadcrumbArray;
	}

	/**
	 * render the breadcrumb
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('breadcrumbStart');
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* breadcrumb keys */

		$breadcrumbKeys = array_keys($this->_breadcrumbArray);
		$lastKey = end($breadcrumbKeys);

		/* process breadcrumb */

		foreach ($this->_breadcrumbArray as $key => $valueArray)
		{
			$title = is_array($valueArray) && array_key_exists('title', $valueArray) ? $valueArray['title'] : null;
			$route = is_array($valueArray) && array_key_exists('route', $valueArray) ? $valueArray['route'] : null;
			if ($title)
			{
				$itemElement->clear();

				/* append link */

				if ($route)
				{
					$itemElement->append(
						$linkElement
							->attr('href', $parameterRoute . $route)
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
	 * @param int $key key of the item
	 */

	protected function _create(int $key = 0)
	{
		$title = $this->_registry->get('useTitle');
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$firstTable = $this->_registry->get('firstTable');
		$fullRoute = $this->_registry->get('fullRoute');
		$lastId = $this->_registry->get('lastId');

		/* handle title */

		if ($title)
		{
			$this->_breadcrumbArray[$key]['title'] = $title;
		}

		/* handle home */

		else if (!$fullRoute)
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('home');
		}

		/* handle administration */

		else if ($firstParameter === 'admin')
		{
			$this->_createAdmin($key);
		}

		/* handle login */

		else if ($firstParameter === 'login')
		{
			if ($secondParameter === 'recover')
			{
				$this->_breadcrumbArray[$key]['title'] = $this->_language->get('recovery');
			}
			else if ($secondParameter === 'reset')
			{
				$this->_breadcrumbArray[$key]['title'] = $this->_language->get('reset');
			}
			else
			{
				$this->_breadcrumbArray[$key]['title'] = $this->_language->get('login');
			}
		}

		/* handle logout */

		else if ($firstParameter === 'logout')
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('logout');
		}

		/* handle register */

		else if ($firstParameter === 'register')
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('registration');
		}

		/* handle module */

		else if ($firstParameter === 'module')
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('module');
		}

		/* handle search */

		else if ($firstParameter === 'search')
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('search');
		}

		/* handle error */

		else if (!$lastId)
		{
			$this->_breadcrumbArray[$key]['title'] = $this->_language->get('error');
		}

		/* handle content */

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
	 * @param int $key key of the item
	 */

	protected function _createAdmin(int $key = 0)
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

			/* set the route */

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
	 * @param int $key
	 */

	protected function _createContent(int $key = 0)
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

		/* set the route */

		if ($firstParameter !== $lastParameter)
		{
			$this->_breadcrumbArray[$key]['route'] = $firstParameter;
		}

		/* join second title */

		if ($secondTable)
		{
			$key++;
			$this->_breadcrumbArray[$key]['title'] = Db::forTablePrefix($secondTable)->where('alias', $secondParameter)->findOne()->title;

			/* set the route */

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
