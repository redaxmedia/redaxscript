<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Dater;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;
use function count;
use function json_decode;

/**
 * children class to create the category form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class CategoryForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return string
	 */

	public function render(int $categoryId = null) : string
	{
		$output = Module\Hook::trigger('adminCategoryFormStart');
		$helperOption = new Helper\Option($this->_language);
		$categoryModel = new Admin\Model\Category();
		$category = $categoryModel->getById($categoryId);
		$nameValidator = new Validator\Name();
		$aliasValidator = new Validator\Alias();
		$dater = new Dater();
		$dater->init($category->date);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($category->id ? $category->title : $this->_language->get('category_new'));
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate rs-admin-js-alias rs-admin-fn-tab rs-admin-component-tab rs-admin-form-default rs-admin-form-category'
			],
			'button' =>
			[
				'create' =>
				[
					'name' => self::class
				],
				'save' =>
				[
					'name' => self::class
				]
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

		/* create the form */

		$formElement

			/* category */

			->radio(
			[
				'id' => self::class . '\Category',
				'class' => 'rs-admin-fn-status-tab',
				'name' => self::class . '\Tab',
				'checked' => 'checked'
			])
			->label($this->_language->get('category'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => self::class . '\Category'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
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
				'pattern' => $nameValidator->getPattern(),
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
				'pattern' => $aliasValidator->getPattern(),
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
				'class' => 'rs-admin-js-resize rs-admin-field-textarea rs-admin-field-small',
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
				'class' => 'rs-admin-js-resize rs-admin-field-textarea rs-admin-field-small',
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
			->select($helperOption->getRobotArray(),
			[
				$category->robots
			],
			[
				'id' => 'robots',
				'name' => 'robots'
			])
			->append('</li></ul>')

			/* general */

			->radio(
			[
				'id' => self::class . '\General',
				'class' => 'rs-admin-fn-status-tab',
				'name' => self::class . '\Tab'
			])
			->label($this->_language->get('general'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => self::class . '\General'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select($helperOption->getLanguageArray(),
			[
				$category->language
			],
			[
				'id' => 'language',
				'name' => 'language'
			])
			->append('</li><li>')
			->label($this->_language->get('template'),
			[
				'for' => 'template'
			])
			->select($helperOption->getTemplateArray(),
			[
				$category->template
			],
			[
				'id' => 'template',
				'name' => 'template'
			])
			->append('</li><li>')
			->label($this->_language->get('category_sibling'),
			[
				'for' => 'sibling'
			])
			->select($helperOption->getSiblingForCategoryArray($category->id),
			[
				$category->sibling
			],
			[
				'id' => 'sibling',
				'name' => 'sibling'
			])
			->append('</li><li>')
			->label($this->_language->get('category_parent'),
			[
				'for' => 'parent'
			])
			->select($helperOption->getParentForCategoryArray($category->id),
			[
				$category->parent
			],
			[
				'id' => 'parent',
				'name' => 'parent'
			])
			->append('</li></ul>')

			/* customize */

			->radio(
			[
				'id' => self::class . '\Customize',
				'class' => 'rs-admin-fn-status-tab',
				'name' => self::class . '\Tab'
			])
			->label($this->_language->get('customize'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => self::class . '\Customize'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->checkbox(!$category->id || $category->status ?
			[
				'id' => 'status',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'status',
				'checked' => 'checked'
			] :
			[
				'id' => 'status',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'status'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'status',
				'data-on' => $this->_language->get('publish'),
				'data-off' => $this->_language->get('unpublish')
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
				'value' => $category->id ? $category->rank : $categoryModel->query()->max('rank') + 1
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
				->select($helperOption->getGroupArray(),
				(array)json_decode($category->access),
				[
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getGroupArray())
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
				'value' => $dater->formatField()
			])
			->append('</li></ul>')
			->hidden(
			[
				'name' => 'id',
				'value' => $category->id
			])
			->token()
			->append('<div class="rs-admin-wrapper-button">')
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
		$formElement->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminCategoryFormEnd');
		return $output;
	}
}
