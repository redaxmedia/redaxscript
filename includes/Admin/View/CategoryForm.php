<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

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
		$output = Hook::trigger('adminCategoryFormStart');
		$category = Db::forTablePrefix('categories')->whereIdIs($categoryId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($category->title ? $category->title : $this->_language->get('category_new'));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'action' => $this->_registry->get('parameterRoute') . ($category->id ? 'admin/process/categories/' . $category->id : 'admin/process/categories'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default rs-admin-fn-clearfix'
			),
			'link' => array(
				'cancel' => array(
					'href' => $this->_registry->get('categoriesEdit') && $this->_registry->get('categoriesDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/categories' : $this->_registry->get('parameterRoute') . 'admin'
				),
				'delete' => array(
					'href' => $category->id ? $this->_registry->get('parameterRoute') . 'admin/delete/categories/' . $category->id . '/' . $this->_registry->get('token') : null
				)
			)
		));

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
			->label($this->_language->get('title'), array(
				'for' => 'title'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'class' => 'rs-admin-js-generate-alias-input rs-admin-field-default rs-admin-field-text',
				'id' => 'title',
				'name' => 'title',
				'required' => 'required',
				'value' => $category->title
			))
			->append('</li><li>')
			->label($this->_language->get('alias'), array(
				'for' => 'alias'
			))
			->text(array(
				'class' => 'rs-admin-js-generate-alias-output rs-admin-field-default rs-admin-field-text',
				'id' => 'alias',
				'name' => 'alias',
				'required' => 'required',
				'value' => $category->alias
			))
			->append('</li><li>')
			->label($this->_language->get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $category->description
			))
			->append('</li><li>')
			->label($this->_language->get('keywords'), array(
				'for' => 'keywords'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-js-generate-keyword-output rs-admin-field-textarea rs-admin-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'value' => $category->keywords
			))
			->append('</li><li>')
			->label($this->_language->get('robots'), array(
				'for' => 'robots'
			))
			->select(Helper\Option::getRobotArray(), array(
				'id' => 'robots',
				'name' => 'robots',
				'value' => $category->id ? intval($category->robots) : 1
			))
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('language'), array(
				'for' => 'language'
			))
			->select(Helper\Option::getLanguageArray(), array(
				'id' => 'language',
				'name' => 'language',
				'value' => $category->language
			))
			->append('</li><li>')
			->label($this->_language->get('template'), array(
				'for' => 'template'
			))
			->select(Helper\Option::getTemplateArray(), array(
				'id' => 'template',
				'name' => 'template',
				'value' => $category->template
			))
			->append('</li><li>')
			->label($this->_language->get('category_sibling'), array(
				'for' => 'sibling'
			))
			->select(Helper\Option::getContentArray('categories'), array(
				'id' => 'sibling',
				'name' => 'sibling',
				'value' => intval($category->sibling)
			))
			->append('</li><li>')
			->label($this->_language->get('category_parent'), array(
				'for' => 'parent'
			))
			->select(Helper\Option::getContentArray('categories'), array(
				'id' => 'parent',
				'name' => 'parent',
				'value' => intval($category->parent)
			))
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label($this->_language->get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getVisibleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $category->id ? intval($category->status) : 1
			))
			->append('</li><li>')
			->label($this->_language->get('rank'), array(
				'for' => 'rank'
			))
			->number(array(
				'id' => 'rank',
				'name' => 'rank',
				'value' => $category->id ? intval($category->rank) : Db::forTablePrefix('categories')->max('rank') + 1
			))
			->append('</li>');
		if ($this->_registry->get('groupsEdit'))
		{
			$formElement
				->append('<li>')
				->label($this->_language->get('access'), array(
					'for' => 'access'
				))
				->select(Helper\Option::getAccessArray('groups'), array(
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count(Helper\Option::getAccessArray('groups')),
					'value' => $category->access
				))
				->append('</li>');
		}
		$formElement
			->append('<li>')
			->label($this->_language->get('date'), array(
				'for' => 'date'
			))
			->datetime(array(
				'id' => 'date',
				'name' => 'date',
				'value' => $category->date ? $category->date : null
			))
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
		$output .= Hook::trigger('adminCategoryFormEnd');
		return $output;
	}
}
