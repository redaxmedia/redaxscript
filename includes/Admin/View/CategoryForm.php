<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the category form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class CategoryForm extends ViewAbstract implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param integer $categoryId identifier of the category
	 *
	 * @return string
	 */

	public function render($categoryId = null)
	{
		$output = Module\Hook::trigger('adminCategoryFormStart');
		$category = Db::forTablePrefix('categories')->whereIdIs($categoryId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-admin-title-content',
		]);
		$titleElement->text($category->title ? $category->title : $this->_language->get('category_new'));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
		]);
		$formElement = new AdminForm($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'action' => $this->_registry->get('parameterRoute') . ($category->id ? 'admin/process/categories/' . $category->id : 'admin/process/categories'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-component-tab rs-admin-form-default rs-admin-fn-clearfix'
			],
			'link' =>
			[
				'cancel' =>
				[
					'href' => $this->_registry->get('categoriesEdit') && $this->_registry->get('categoriesDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/categories' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'delete' =>
				[
					'href' => $category->id ? $this->_registry->get('parameterRoute') . 'admin/delete/categories/' . $category->id . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* collect item output */

		$tabRoute = $this->_registry->get('parameterRoute') . $this->_registry->get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text($this->_language->get('category'))
			);
		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-2')
				->text($this->_language->get('general'))
			);
		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-3')
				->text($this->_language->get('customize'))
			);
		$listElement->append($outputItem);

		/* create the form */

		$formElement
			->append($listElement)
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-1" class="rs-admin-js-set-tab rs-admin-js-set-active rs-admin-set-tab rs-admin-set-active"><ul><li>')
			->label($this->_language->get('title'),
			[
				'for' => 'title'
			])
			->text(
			[
				'autofocus' => 'autofocus',
				'class' => 'rs-admin-js-alias-input rs-admin-field-default rs-admin-field-text',
				'id' => 'title',
				'name' => 'title',
				'required' => 'required',
				'value' => $category->title
			])
			->append('</li><li>')
			->label($this->_language->get('alias'),
			[
				'for' => 'alias'
			])
			->text(
			[
				'class' => 'rs-admin-js-alias-output rs-admin-field-default rs-admin-field-text',
				'id' => 'alias',
				'name' => 'alias',
				'pattern' => '[a-zA-Z0-9-]+',
				'required' => 'required',
				'value' => $category->alias
			])
			->append('</li><li>')
			->label($this->_language->get('description'),
			[
				'for' => 'description'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'rows' => 1,
				'value' => $category->description
			])
			->append('</li><li>')
			->label($this->_language->get('keywords'),
			[
				'for' => 'keywords'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-auto-resize rs-admin-js-generate-keyword-output rs-admin-field-textarea rs-admin-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'rows' => 1,
				'value' => $category->keywords
			])
			->append('</li><li>')
			->label($this->_language->get('robots'),
			[
				'for' => 'robots'
			])
			->select(Helper\Option::getRobotArray(),
			[
				'id' => 'robots',
				'name' => 'robots',
				'value' => $category->id ? filter_var($category->robots, FILTER_VALIDATE_INT) : null
			])
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select(Helper\Option::getLanguageArray(),
			[
				'id' => 'language',
				'name' => 'language',
				'value' => $category->language
			])
			->append('</li><li>')
			->label($this->_language->get('template'),
			[
				'for' => 'template'
			])
			->select(Helper\Option::getTemplateArray(),
			[
				'id' => 'template',
				'name' => 'template',
				'value' => $category->template
			])
			->append('</li><li>')
			->label($this->_language->get('category_sibling'),
			[
				'for' => 'sibling'
			])
			->select(Helper\Option::getContentArray('categories',
			[
				intval($category->id)
			]),
			[
				'id' => 'sibling',
				'name' => 'sibling',
				'value' => intval($category->sibling)
			])
			->append('</li><li>')
			->label($this->_language->get('category_parent'),
			[
				'for' => 'parent'
			])
			->select(Helper\Option::getContentArray('categories',
			[
				intval($category->id)
			]),
			[
				'id' => 'parent',
				'name' => 'parent',
				'value' => intval($category->parent)
			])
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->select(Helper\Option::getVisibleArray(),
			[
				'id' => 'status',
				'name' => 'status',
				'value' => $category->id ? intval($category->status) : 1
			])
			->append('</li><li>')
			->label($this->_language->get('rank'),
			[
				'for' => 'rank'
			])
			->number(
			[
				'id' => 'rank',
				'name' => 'rank',
				'value' => $category->id ? intval($category->rank) : Db::forTablePrefix('categories')->max('rank') + 1
			])
			->append('</li>');
		if ($this->_registry->get('groupsEdit'))
		{
			$formElement
				->append('<li>')
				->label($this->_language->get('access'),
				[
					'for' => 'access'
				])
				->select(Helper\Option::getAccessArray('groups'),
				[
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count(Helper\Option::getAccessArray('groups')),
					'value' => $category->access
				])
				->append('</li>');
		}
		$formElement
			->append('<li>')
			->label($this->_language->get('date'),
			[
				'for' => 'date'
			])
			->datetime(
			[
				'id' => 'date',
				'name' => 'date',
				'value' => $category->date ? $category->date : null
			])
			->append('</li></ul></fieldset></div>')
			->token()
			->cancel();
		if ($category->id)
		{
			if ($this->_registry->get('categoriesDelete'))
			{
				$formElement->delete();
			}
			if ($this->_registry->get('categoriesEdit'))
			{
				$formElement->save();
			}
		}
		else if ($this->_registry->get('categoriesNew'))
		{
			$formElement->create();
		}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminCategoryFormEnd');
		return $output;
	}
}
