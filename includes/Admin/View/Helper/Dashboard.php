<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;
use function array_replace_recursive;

/**
 * helper class to create the admin dashboard
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Dashboard extends Admin\View\ViewAbstract
{
	/**
	 * options of the panel
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-admin-list-dashboard',
			'item' => 'rs-admin-item-dashboard'
		]
	];

	/**
	 * init the class
	 *
	 * @since 4.1.0
	 *
	 * @param array $optionArray options of the dashboard
	 *
	 * @return self
	 */

	public function init(array $optionArray = []) : self
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		return $this;
	}

	/**
	 * render the view
	 *
	 * @since 4.1.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminDashboardStart');
		$outputItem = null;
		$dashboardArray = Module\Hook::collect('adminDashboard');

		/* html element */

		$element = new Html\Element();
		$listElement = $element->copy()->init('ul',
		[
			'class' => $this->_optionArray['className']['list']
		]);
		$itemElement = $element->copy()->init('li',
		[
			'class' => $this->_optionArray['className']['item']
		]);

		/* collect item output */

		foreach ($dashboardArray as $moduleArray)
		{
			foreach ($moduleArray as $valueArray)
			{
				$outputItem .= $itemElement
					->copy()
					->attr('data-column', $valueArray['column'] ? : 1)
					->html($valueArray['content']);
			}
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		$output .= Module\Hook::trigger('adminDashboardEnd');
		return $output;
	}
}
