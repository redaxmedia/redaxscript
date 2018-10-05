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
		'orderColumn' => 'rank',
		'parentId' => 0
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
			->where('status', 1)
			->orderBySetting($this->_optionArray['orderColumn'])
			->limit($this->_optionArray['limit'])
			->findMany();

		/* collect output */

		$output .= $this->renderList($categories, $this->_optionArray);
		$output .= Module\Hook::trigger('navigationCategoryEnd');
		return $output;
	}

	/**
	 * render the list
	 *
	 * @since 3.3.0
	 *
	 * @param object $categories
	 * @param array $optionArray
	 *
	 * @return string|null
	 */

	protected function renderList(object $categories = null, array $optionArray = []) : ?string
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
				'class' => $optionArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');

		/* collect item output */

		foreach ($categories as $value)
		{
			if ($accessValidator->validate($value->access, $this->_registry->get('myGroups')) && $optionArray['parentId'] === (int)$value->parent)
			{
				$outputItem .= $itemElement
					->copy()
					->addClass((int)$this->_registry->get('categoryId') === (int)$value->id ? $this->_optionArray['className']['active'] : null)
					->html($linkElement
						->copy()
						->attr(
						[
							'href' => $this->_registry->get('parameterRoute') . $categoryModel->getRouteById($value->id)
						])
						->text($value->title)
					)
					->append($this->renderList($categories,
					[
						'className' =>
						[
							'list' => $value->parent ? $optionArray['className']['list'] : $optionArray['className']['children'],
							'active' => $optionArray['className']['active'],
						],
						'parentId' => (int)$value->id
					]));
			}
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		return $output;
	}
}
