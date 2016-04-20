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
			'class' => 'rs-admin-js-list-tab rs-admin-list-tab'
		));
		$formElement = new AdminForm(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'action' => Registry::get('parameterRoute') . ($article->id ? 'admin/process/articles/' . $article->id : 'admin/process/articles'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-form-default'
			),
			'link' => array(
				'cancel' => array(
					'href' => Registry::get('articlesEdit') && Registry::get('articlesDelete') ? Registry::get('parameterRoute') . 'admin/view/articles' : Registry::get('parameterRoute') . 'admin'
				),
				'delete' => array(
					'href' => $article->id ? Registry::get('parameterRoute') . 'admin/delete/articles/' . $article->id . '/' . Registry::get('token') : null
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
				'value' => $article->title
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
				'value' => $article->alias
			))
			->append('</li><li>')
			->label(Language::get('description'), array(
				'for' => 'description'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small',
				'id' => 'description',
				'name' => 'description',
				'value' => $article->description
			))
			->append('</li><li>')
			->label(Language::get('keywords'), array(
				'for' => 'keywords'
			))
			->textarea(array(
				'class' => 'rs-admin-js-auto-resize rs-admin-js-generate-keyword-output rs-admin-field-textarea rs-admin-field-small',
				'id' => 'keywords',
				'name' => 'keywords',
				'value' => $article->keywords
			))
			->append('</li><li>')
			->label(Language::get('robots'), array(
				'for' => 'robots'
			))
			->select(Helper\Option::getRobotArray(), array(
				'id' => 'robots',
				'name' => 'robots',
				'value' => $article->id ? intval($article->robots) : 1
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
				'value' => htmlspecialchars($article->text)
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
				'value' => intval($article->sibling)
			))
			->append('</li><li>')
			->label(Language::get('category'), array(
				'for' => 'category'
			))
			->select(Helper\Option::getContentArray('categories'), array(
				'id' => 'category',
				'name' => 'category',
				'value' => intval($article->category)
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
				'value' => $article->id ? intval($article->headline) : 1
			))
			->append('</li><li>')
			->label(Language::get('infoline'), array(
				'for' => 'infoline'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'infoline',
				'name' => 'infoline',
				'value' => $article->id ? intval($article->infoline) : 1
			))
			->append('</li><li>')
			->label(Language::get('comments'), array(
				'for' => 'comments'
			))
			->select(Helper\Option::getToggleArray(), array(
				'id' => 'comments',
				'name' => 'comments',
				'value' => intval($article->comments)
			))
			->append('</li><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getVisibleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $article->id ? intval($article->status) : 1
			))
			->append('</li><li>')
			->label(Language::get('rank'), array(
				'for' => 'rank'
			))
			->number(array(
				'id' => 'rank',
				'name' => 'rank',
				'value' => $article->id ? intval($article->rank) : Db::forTablePrefix('articles')->max('rank') + 1
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
					'value' => $article->access
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
				'value' => $article->date ? $article->date : null
			))
			->append('</li></ul></fieldset></div>')
			->token()
			->cancel();
		if ($article->id)
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
		$output .= Hook::trigger('adminArticleFormEnd');
		return $output;
	}
}
