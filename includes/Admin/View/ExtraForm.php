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
 * children class to generate the extra form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class ExtraForm implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param integer $extraId identifier of the extra
	 *
	 * @return string
	 */

	public function render($extraId = null)
	{
		$output = Hook::trigger('adminExtraFormStart');
		$extra = Db::forTablePrefix('extras')->whereIdIs($extraId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($extra->title ? $extra->title : Language::get('extra_new'));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => Registry::get('parameterRoute') . ($extra->id ? 'admin/process/extras/' . $extra->id : 'admin/process/extras'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default'
			),
			'link' => array(
				'cancel' => array(
					'href' => Registry::get('extrasEdit') && Registry::get('extrasDelete') ? Registry::get('parameterRoute') . 'admin/view/extras' : Registry::get('parameterRoute') . 'admin'
				),
				'delete' => array(
					'href' => $extra->id ? Registry::get('parameterRoute') . 'admin/delete/extras/' . $extra->id . '/' . Registry::get('token') : null
				)
			)
		));

		/* collect item output */

		$tabRoute = Registry::get('parameterRoute') . Registry::get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-admin-js-item-active rs-admin-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text(Language::get('extra'))
			);
		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-2')
				->text(Language::get('general'))
			);
		$outputItem .= $itemElement
			->copy()
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-3')
				->text(Language::get('customize'))
			);
		$listElement->append($outputItem);

		/* create the form */

		$formElement
			->append($listElement)
			->append('<div class="rs-admin-js-box-tab rs-admin-box-tab">')

			/* first tab */

			->append('<fieldset id="tab-1" class="rs-admin-js-set-tab rs-admin-js-set-active rs-admin-set-tab rs-admin-set-active"><ul><li>')
			->label(Language::get('title'), array(
				'for' => 'title'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'class' => 'rs-admin-js-generate-alias-input rs-admin-field-default rs-admin-field-text',
				'id' => 'title',
				'name' => 'title',
				'required' => 'required',
				'value' => $extra->title
			))
			->append('</li><li>')
			->label(Language::get('alias'), array(
				'for' => 'alias'
			))
			->text(array(
				'class' => 'rs-admin-js-generate-alias-output rs-admin-field-default rs-admin-field-text',
				'id' => 'alias',
				'name' => 'alias',
				'required' => 'required',
				'value' => $extra->alias
			))
			->append('</li><li>')
			->label(Language::get('text'), array(
				'for' => 'text'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-js-generate-keyword-input rs-admin-js-editor-textarea rs-admin-field-textarea',
				'id' => 'text',
				'name' => 'text',
				'required' => 'required',
				'value' => htmlspecialchars($extra->text)
			))
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label(Language::get('language'), array(
				'for' => 'language'
			))
			->select(Helper\Option::getLanguageArray(), array(
				'id' => 'language',
				'name' => 'language',
				'value' => $extra->language
			))
			->append('</li><li>')
			->label(Language::get('extra_sibling'), array(
				'for' => 'sibling'
			))
			->select(Helper\Option::getContentArray('extras'), array(
				'id' => 'sibling',
				'name' => 'sibling',
				'value' => intval($extra->sibling)
			))
			->append('</li><li>')
			->label(Language::get('category'), array(
				'for' => 'category'
			))
			->select(Helper\Option::getContentArray('categories'), array(
				'id' => 'category',
				'name' => 'category',
				'value' => intval($extra->category)
			))
			->append('</li><li>')
			->label(Language::get('article'), array(
				'for' => 'article'
			))
			->select(Helper\Option::getContentArray('articles'), array(
				'id' => 'article',
				'name' => 'article',
				'value' => intval($extra->article)
			))
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-admin-js-set-tab rs-admin-set-tab"><ul><li>')
			->label(Language::get('headline'), array(
				'for' => 'headline'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'headline',
				'name' => 'headline',
				'value' => $extra->id ? intval($extra->headline) : 1
			))
			->append('</li><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getVisibleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $extra->id ? intval($extra->status) : 1
			))
			->append('</li><li>')
			->label(Language::get('rank'), array(
				'for' => 'rank'
			))
			->number(array(
				'id' => 'rank',
				'name' => 'rank',
				'value' => $extra->id ? intval($extra->rank) : Db::forTablePrefix('extras')->max('rank') + 1
			))
			->append('</li>');
		if (Registry::get('groupsEdit'))
		{
			$formElement
				->append('<li>')
				->label(Language::get('access'), array(
					'for' => 'access'
				))
				->select(Helper\Option::getAccessArray('groups'), array(
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count(Helper\Option::getAccessArray('groups')),
					'value' => $extra->access
				))
				->append('</li>');
		}
		$formElement
			->append('<li>')
			->label(Language::get('date'), array(
				'for' => 'date'
			))
			->datetime(array(
				'id' => 'date',
				'name' => 'date',
				'value' => $extra->date ? $extra->date : null
			))
			->append('</li></ul></fieldset></div>')
			->token()
			->cancel();
		if ($extra->id)
		{
			$formElement
				->delete()
				->save();
		}
		else
		{
			$formElement->create();
		}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminExtraFormEnd');
		return $output;
	}
}
