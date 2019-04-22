<?php
namespace Redaxscript\Navigation;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the category navigation
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Navigation
 * @author Henry Ruhs
 */

class Category extends NavigationAbstract
{
	/**
	 * options of the navigation
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-categories',
			'children' => 'rs-list-children',
			'active' => 'rs-item-active'
		],
		'orderColumn' => 'rank'
	];

	/**
	 * render the view
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('navigationCategoryStart');
		$categoryModel = new Model\Category();

		/* query categories */

		$categories = $categoryModel
			->query()
			->whereLanguageIs($this->_registry->get('language'))
			->whereNull('parent')
			->where('status', 1)
			->orderBySetting($this->_optionArray['orderColumn'])
			->limit($this->_optionArray['limit'])
			->findMany();

		/* collect output */

		$output .= $this->_renderList($categories);
		$output .= Module\Hook::trigger('navigationCategoryEnd');
		return $output;
	}

	/**
	 * render the list
	 *
	 * @since 3.3.0
	 *
	 * @param object $categories
	 * @param int $level
	 *
	 * @return string|null
	 */

	protected function _renderList(object $categories = null, int $level = 0) : ?string
	{
		$output = null;
		$outputItem = null;
		$categoryModel = new Model\Category();
		$accessValidator = new Validator\Access();

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $level === 0 ? $this->_optionArray['className']['list'] : $this->_optionArray['className']['children']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');
		$textElement = $element->copy()->init('span');

		/* collect item output */

		foreach ($categories as $value)
		{
			if ($accessValidator->validate($value->access, $this->_registry->get('myGroups')))
			{
				$outputItem .= $itemElement
					->copy()
					->addClass($this->_registry->get('firstParameter') === $value->alias ? $this->_optionArray['className']['active'] : null)
					->html($linkElement
						->copy()
						->attr(
						[
							'href' => $this->_registry->get('parameterRoute') . $categoryModel->getRouteById($value->id)
						])
						->text($value->title)
					)
					->append(
						$this->_renderList($categoryModel
							->query()
							->whereLanguageIs($this->_registry->get('language'))
							->where(
							[
								'parent' => $value->id,
								'status' => 1
							])
							->orderBySetting($this->_optionArray['orderColumn'])
							->limit($this->_optionArray['limit'])
							->findMany(), ++$level
						)
					);
			}
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		else if ($level === 0)
		{
			$output .= $listElement->html($itemElement->html($textElement->text($this->_language->get('category_no'))));
		}
		return $output;
	}
}
