<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Admin\View\Helper;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the category form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class CategoryForm implements ViewInterface
{
	/**
	 * stringify the view
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
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param integer $categoryId identifer of the category
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
		$titleElement->text($category->title ? $category->title : Language::get('category_new'));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => 'rs-js-list-tab rs-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => 'admin/process',
				'class' => 'rs-js-tab rs-js-validate-form rs-admin-form-default'
			),
			'button' => array(
				'submit' => array(
					'class' => 'rs-js-submit rs-admin-button-default rs-admin-button-submit rs-admin-button-large',
					'name' => Registry::get('adminParameter')
				)
			)
		));
		$linkCancel = new Html\Element();
		$linkCancel
			->init('a', array(
				'class' => 'rs-js-cancel rs-admin-button-default rs-admin-button-cancel rs-admin-button-large',
				'href' => 'admin/view/categories'
			))
			->text(Language::get('cancel'));
		$linkDelete = new Html\Element();
		$linkDelete
			->init('a', array(
				'class' => 'rs-js-delete rs-js-confirm rs-admin-button-default rs-admin-button-delete rs-admin-button-large',
				'href' => 'admin/delete/categories/' . $category->id . '/' . Registry::get('token')
			))
			->text(Language::get('delete'));

		/* collect item output */

		$outputItem = $itemElement
			->copy()
			->addClass('rs-js-item-active rs-item-first rs-item-active')
			->html($linkElement
				->copy()
				->attr('href', Registry::get('rewriteRoute') . Registry::get('fullRoute') . '#tab-1')
				->text(Language::get('category'))
			);
		$outputItem .= $itemElement
			->copy()
			->addClass('rs-item-second')
			->html($linkElement
				->copy()
				->attr('href', Registry::get('rewriteRoute') . Registry::get('fullRoute') . '#tab-2')
				->text(Language::get('general'))
		);
		$outputItem .= $itemElement
			->copy()
			->addClass('rs-item-last')
			->html($linkElement
				->copy()
				->attr('href', Registry::get('rewriteRoute') . Registry::get('fullRoute') . '#tab-3')
				->text(Language::get('customize'))
		);
		$listElement->append($outputItem);

		/* create the form */

		$formElement
			->append($listElement)
			->append('<div class="rs-js-box-tab rs-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-1" class="rs-js-set-tab rs-js-set-active rs-set-tab rs-set-active"><ul><li>')
			->label(Language::get('title'), array(
				'for' => 'title'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'class' => 'rs-js-generate-alias-input rs-admin-field-default rs-admin-field-text',
				'id' => 'title',
				'name' => 'title',
				'required' => 'required',
				'value' => $category->title
			))
			->append('</li><li>')
			->label(Language::get('alias'), array(
				'for' => 'alias'
			))
			->text(array(
				'class' => 'rs-js-generate-alias-output rs-admin-field-default rs-admin-field-text',
				'id' => 'alias',
				'name' => 'alias',
				'required' => 'required',
				'value' => $category->alias
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-admin-field-textarea rs-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $category->description
			))
			->append('</li><li>')
			->label(Language::get('keywords'), array(
				'for' => 'keywords'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-js-generate-keyword-output rs-admin-field-textarea rs-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'value' => $category->keywords
			))
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('language'), array(
				'for' => 'language'
			))
			->select(Helper\Option::getLanguageArray(), array(
				'id' => 'language',
				'name' => 'language',
				'value' => $category->language
			))
			->append('</li><li>')
			->label(Language::get('template'), array(
				'for' => 'template'
			))
			->select(Helper\Option::getTemplateArray(), array(
				'id' => 'template',
				'name' => 'template',
				'value' => $category->template
			))
			->append('</li><li>')
			->label(Language::get('category_sibling'), array(
				'for' => 'sibling'
			))
			->select(Helper\Option::getContentArray('categories'), array(
				'id' => 'sibling',
				'name' => 'sibling',
				'value' => $category->sibling
			))
			->append('</li><li>')
			->label(Language::get('category_parent'), array(
				'for' => 'parent'
			))
			->select(Helper\Option::getContentArray('categories'), array(
				'id' => 'parent',
				'name' => 'parent',
				'value' => $category->parent
			))
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getStatusArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $category->status
			))
			->append('</li><li>')
			->label(Language::get('access'), array(
				'for' => 'access'
			))
			->select(Helper\Option::getAccessArray('groups'), array(
				'id' => 'access',
				'name' => 'access',
				'multiple' => 'multiple',
				'size' => count(Helper\Option::getAccessArray('groups')),
				'value' => $category->access
			))
			->append('</li><li>')
			->label(Language::get('date'), array(
				'for' => 'date'
			))
			->datetime(array(
				'id' => 'date',
				'name' => 'date',
				'value' => $category->date
			))
			->append('</li></ul></fieldset></div>')
			->token()
			->append($linkCancel);
			if ($category->id)
			{
				$formElement->append($linkDelete);
			}
			$formElement->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminCategoryFormEnd');
		return $output;
	}
}
