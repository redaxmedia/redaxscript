<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to render the article form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class ArticleForm implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = Hook::trigger('adminArticleFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text(Language::get('article_new'));
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
					'name' => get_class()
				)
			)
		));

		/* collect item output */

		$outputItem = $itemElement
			->copy()
			->addClass('rs-js-item-active rs-item-first rs-item-active')
			->html($linkElement
				->copy()
				->attr('href', Registry::get('rewriteRoute') . Registry::get('fullRoute') . '#tab-1')
				->text(Language::get('article'))
			);
		$outputItem .= $itemElement
			->copy()
			->addClass('rs-item-last')
			->html($linkElement
				->copy()
				->attr('href', Registry::get('rewriteRoute') . Registry::get('fullRoute') . '#tab-2')
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
				'class' => 'rs-js-generate-alias-input rs-admin-field-text',
				'id' => 'title',
				'name' => 'title',
				'required' => 'required'
			))
			->append('</li><li>')
			->label(Language::get('alias'), array(
				'for' => 'alias'
			))
			->text(array(
				'class' => 'rs-js-generate-alias-output rs-admin-field-text',
				'id' => 'alias',
				'name' => 'alias',
				'required' => 'required'
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-admin-field-textarea rs-field-small',
				'id' => 'description',
				'name' => 'description',
			))
			->append('</li><li>')
			->label(Language::get('keywords'), array(
				'for' => 'keywords'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-js-generate-keyword-output rs-admin-field-textarea rs-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
			))
			->append('</li><li>')
			->label(Language::get('text'), array(
				'for' => 'text'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-js-generate-keyword-input rs-js-editor-textarea rs-admin-field-textarea rs-field-note',
				'id' => 'text',
				'name' => 'text',
			))
			->append('</li></ul></fieldset>')

			/* second tab */

			->append('<fieldset id="tab-2" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('language'), array(
				'for' => 'language'
			))
			->select(array(
				Language::get('select') => null
			), array(
				'id' => 'language',
				'name' => 'language',
			))
			->append('</li></ul></fieldset></div>')
			->token()
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('loginEnd');
		return $output;
	}
}
