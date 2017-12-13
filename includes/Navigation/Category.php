<?php
namespace Redaxscript\Navigation;

use IdiormResultSet as DbResultSet;
use Redaxscript\Db;
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
		'parent' => 0,
		'children' => 0
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

		/* query articles */

		$query = Db::forTablePrefix('categories')
			->where('status', 1)
			->whereLanguageIs($this->_registry->get('language'))
			->limit($this->_optionArray['limit']);
		$categories = $this->_optionArray['order'] === 'asc' ? $query->orderByAsc('rank')->findMany() : $query->orderByDesc('rank')->findMany();

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
	 * @param DbResultSet $categories
	 * @param array $optionArray
	 *
	 * @return string|null
	 */

	protected function renderList(DbResultSet $categories = null, array $optionArray = [])
	{
		$output = null;
		$outputItem = null;
		$contentModel = new Model\Content();
		$accessValidator = new Validator\Access();

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $optionArray['className']['list']
		]);
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$linkElement = new Html\Element();
		$linkElement->init('a');

		/* collect item output */

		foreach ($categories as $value)
		{
			if ($accessValidator->validate($value->access, $this->_registry->get('myGroups')) === Validator\ValidatorInterface::PASSED && $optionArray['parent'] === intval($value->parent))
			{
				$outputItem .= $itemElement
					->copy()
					->addClass(intval($this->_registry->get('categoryId')) === intval($value->id) ? $this->_optionArray['className']['active'] : null)
					->html($linkElement
						->copy()
						->attr(
						[
							'href' => $this->_registry->get('parameterRoute') . $contentModel->getRouteByTableAndId('categories', $value->id)
						])
						->text($value->title)
					)
					->append($optionArray['children'] > 0 ? $this->renderList($categories,
					[
						'className' =>
						[
							'list' => $value->parent ? $optionArray['className']['list'] : $optionArray['className']['children'],
							'active' => $optionArray['className']['active'],
						],
						'parent' => intval($value->id),
						'children' => $optionArray['children']
					]) : null);
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
