<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Dater;
use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;
use function count;
use function htmlspecialchars;
use function json_decode;

/**
 * children class to create the extra form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ExtraForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int $extraId identifier of the extra
	 *
	 * @return string
	 */

	public function render(int $extraId = null) : string
	{
		$output = Module\Hook::trigger('adminExtraFormStart');
		$helperOption = new Helper\Option($this->_language);
		$extraModel = new Admin\Model\Extra();
		$extra = $extraModel->getById($extraId);
		$aliasValidator = new Validator\Alias();
		$dater = new Dater();
		$dater->init($extra->date);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($extra->title ? : $this->_language->get('extra_new'));
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate rs-admin-js-alias rs-admin-fn-tab rs-admin-component-tab rs-admin-form-default'
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
					'href' => $this->_registry->get('extrasEdit') && $this->_registry->get('extrasDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/extras' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'delete' =>
				[
					'href' => $extra->id ? $this->_registry->get('parameterRoute') . 'admin/delete/extras/' . $extra->id . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* create the form */

		$formElement

			/* extra */

			->radio(
			[
				'id' => self::class . '\Extra',
				'class' => 'rs-admin-fn-status-tab',
				'name' => self::class . '\Tab',
				'checked' => 'checked'
			])
			->label($this->_language->get('extra'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => self::class . '\Extra'
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
				'required' => 'required',
				'value' => $extra->title
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
				'pattern' => $aliasValidator->getFormPattern(),
				'required' => 'required',
				'value' => $extra->alias
			])
			->append('</li><li>')
			->label($this->_language->get('text'),
			[
				'for' => 'text'
			])
			->textarea(
			[
				'class' => 'rs-admin-js-editor rs-admin-js-resize rs-admin-field-textarea',
				'id' => 'text',
				'name' => 'text',
				'required' => 'required',
				'value' => htmlspecialchars($extra->text, ENT_QUOTES)
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
				$extra->language
			],
			[
				'id' => 'language',
				'name' => 'language'
			])
			->append('</li><li>')
			->label($this->_language->get('extra_sibling'),
			[
				'for' => 'sibling'
			])
			->select($helperOption->getSiblingForExtraArray($extra->id),
			[
				$extra->sibling
			],
			[
				'id' => 'sibling',
				'name' => 'sibling'
			])
			->append('</li><li>')
			->label($this->_language->get('category'),
			[
				'for' => 'category'
			])
			->select($helperOption->getCategoryArray(),
			[
				$extra->category
			],
			[
				'id' => 'category',
				'name' => 'category'
			])
			->append('</li><li>')
			->label($this->_language->get('article'),
			[
				'for' => 'article'
			])
			->select($helperOption->getArticleArray(),
			[
				$extra->article
			],
			[
				'id' => 'article',
				'name' => 'article'
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
			->label($this->_language->get('headline'),
			[
				'for' => 'headline'
			])
			->checkbox(!$extra->id || $extra->headline ?
			[
				'id' => 'headline',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'headline',
				'checked' => 'checked'
			] :
			[
				'id' => 'headline',
				'class' => 'rs-admin-fn-status-switch',
				'name' => 'headline'
			])
			->label(null,
			[
				'class' => 'rs-admin-label-switch',
				'for' => 'headline',
				'data-on' => $this->_language->get('enable'),
				'data-off' => $this->_language->get('disable')
			])
			->append('</li><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->checkbox(!$extra->id || $extra->status ?
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
				'value' => $extra->id ? $extra->rank : $extraModel->query()->max('rank') + 1
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
				(array)json_decode($extra->access),
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
				'value' => $extra->id
			])
			->token()
			->append('<div class="rs-admin-wrapper-button">')
			->cancel();
		if ($extra->id)
		{
			if ($this->_registry->get('extrasDelete'))
			{
				$formElement->delete();
			}
			if ($this->_registry->get('extrasEdit'))
			{
				$formElement->save();
			}
		}
		else if ($this->_registry->get('extrasNew'))
		{
			$formElement->create();
		}
		$formElement->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminExtraFormEnd');
		return $output;
	}
}
