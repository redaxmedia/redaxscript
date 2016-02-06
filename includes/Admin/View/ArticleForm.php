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
 * children class to generate the article form
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
	 * @param integer $articleId identifier of the article
	 *
	 * @return string
	 */

	public function render($articleId = null)
	{
		$output = Hook::trigger('adminArticleFormStart');
		$article = Db::forTablePrefix('articles')->whereIdIs($articleId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($article->title ? $article->title : Language::get('article_new'));
		$linkElement = new Html\Element();
		$linkElement->init('a');
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$listElement = new Html\Element();
		$listElement->init('ul', array(
			'class' => 'rs-js-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => 'admin/process/articles',
				'class' => 'rs-js-tab rs-js-validate-form rs-admin-form-default'
			),
			'button' => array(
				'submit' => array(
					'name' => Registry::get('adminParameter')
				)
			)
		));
		$linkCancel = new Html\Element();
		$linkCancel
			->init('a', array(
				'class' => 'rs-js-cancel rs-admin-button-default rs-admin-button-cancel rs-admin-button-large',
				'href' => 'admin/view/articles'
			))
			->text(Language::get('cancel'));
		if ($article->id)
		{
			$linkDelete = new Html\Element();
			$linkDelete
				->init('a', array(
					'class' => 'rs-js-delete rs-js-confirm rs-admin-button-default rs-admin-button-delete rs-admin-button-large',
					'href' => 'admin/delete/articles/' . $article->id . '/' . Registry::get('token')
				))
				->text(Language::get('delete'));
		}

		/* collect item output */

		$tabRoute = Registry::get('rewriteRoute') . Registry::get('fullRoute');
		$outputItem = $itemElement
			->copy()
			->addClass('rs-js-item-active rs-item-active')
			->html($linkElement
				->copy()
				->attr('href', $tabRoute . '#tab-1')
				->text(Language::get('article'))
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
			->append('<div class="rs-js-box-tab rs-admin-box-tab">')

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
				'value' => $article->title
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
				'value' => $article->alias
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-admin-field-textarea rs-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $article->description
			))
			->append('</li><li>')
			->label(Language::get('keywords'), array(
				'for' => 'keywords'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-js-generate-keyword-output rs-admin-field-textarea rs-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'value' => $article->keywords
			))
			->append('</li><li>')
			->label(Language::get('text'), array(
				'for' => 'text'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-js-generate-keyword-input rs-js-editor-textarea rs-admin-field-textarea',
				'id' => 'text',
				'name' => 'text',
				'required' => 'required',
				'value' => htmlspecialchars($article->text)
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
				'value' => $article->language
			))
			->append('</li><li>')
			->label(Language::get('template'), array(
				'for' => 'template'
			))
			->select(Helper\Option::getTemplateArray(), array(
				'id' => 'template',
				'name' => 'template',
				'value' => $article->template
			))
			->append('</li><li>')
			->label(Language::get('article_sibling'), array(
				'for' => 'sibling'
			))
			->select(Helper\Option::getContentArray('articles'), array(
				'id' => 'sibling',
				'name' => 'sibling',
				'value' => $article->sibling
			))
			->append('</li><li>')
			->label(Language::get('category'), array(
				'for' => 'category'
			))
			->select(Helper\Option::getContentArray('categories'), array(
				'id' => 'category',
				'name' => 'category',
				'value' => $article->category
			))
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('headline'), array(
				'for' => 'headline'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'headline',
				'name' => 'headline',
				'value' => $article->headline
			))
			->append('</li><li>')
			->label(Language::get('infoline'), array(
				'for' => 'infoline'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'infoline',
				'name' => 'infoline',
				'value' => $article->infoline
			))
			->append('</li><li>')
			->label(Language::get('comments'), array(
				'for' => 'comments'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'comments',
				'name' => 'comments',
				'value' => $article->comments
			))
			->append('</li><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getVisibleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $article->status
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
				'value' => $article->access
			))
			->append('</li><li>')
			->label(Language::get('date'), array(
				'for' => 'date'
			))
			->datetime(array(
				'id' => 'date',
				'name' => 'date',
				'value' => date('Y-m-d\TH:i:s', strtotime($article->date))
			))
			->append('</li></ul></fieldset></div>')
			->token()
			->append($linkCancel);
			if ($article->id)
			{
				$formElement
					->append($linkDelete)
					->submit(Language::get('save'));
			}
			else
			{
				$formElement->submit(Language::get('create'));
			}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminArticleFormEnd');
		return $output;
	}
}
