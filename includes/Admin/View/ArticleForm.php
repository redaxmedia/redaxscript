<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Dater;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the article form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ArticleForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return string
	 */

	public function render(int $articleId = null) : string
	{
		$output = Module\Hook::trigger('adminArticleFormStart');
		$helperOption = new Helper\Option($this->_language);
		$articleModel = new Admin\Model\Article();
		$article = $articleModel->getById($articleId);
		$dater = new Dater();
		$dater->init($article->date);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($article->title ? $article->title : $this->_language->get('article_new'));
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
					'name' => get_class()
				],
				'save' =>
				[
					'name' => get_class()
				]
			],
			'link' =>
			[
				'cancel' =>
				[
					'href' => $this->_registry->get('articlesEdit') && $this->_registry->get('articlesDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/articles' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'delete' =>
				[
					'href' => $article->id ? $this->_registry->get('parameterRoute') . 'admin/delete/articles/' . $article->id . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* create the form */

		$formElement

			/* article */

			->radio(
			[
				'id' => get_class() . '\Article',
				'class' => 'rs-admin-fn-status-tab',
				'name' => get_class() . '\Tab',
				'checked' => 'checked'
			])
			->label($this->_language->get('article'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => get_class() . '\Article'
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
				'value' => $article->title
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
				'value' => $article->alias
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
				'value' => $article->description
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
				'value' => $article->keywords
			])
			->append('</li><li>')
			->label($this->_language->get('robots'),
			[
				'for' => 'robots'
			])
			->select($helperOption->getRobotArray(),
			[
				$article->id ? filter_var($article->robots, FILTER_VALIDATE_INT) : null
			],
			[
				'id' => 'robots',
				'name' => 'robots'
			])
			->append('</li><li>')
			->label($this->_language->get('text'),
			[
				'for' => 'text'
			])
			->textarea(
			[
				'id' => 'text',
				'name' => 'text',
				'required' => 'required',
				'value' => htmlspecialchars($article->text)
			])
			->append('</li></ul>')

			/* general */

			->radio(
			[
				'id' => get_class() . '\General',
				'class' => 'rs-admin-fn-status-tab',
				'name' => get_class() . '\Tab'
			])
			->label($this->_language->get('general'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => get_class() . '\General'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('language'),
			[
				'for' => 'language'
			])
			->select($helperOption->getLanguageArray(),
			[
				$article->language
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
				$article->template
			],
			[
				'id' => 'template',
				'name' => 'template'
			])
			->append('</li><li>')
			->label($this->_language->get('article_sibling'),
			[
				'for' => 'sibling'
			])
			->select($helperOption->getContentArray('articles',
			[
				(int)$article->id
			]),
			[
				(int)$article->sibling
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
			->select($helperOption->getContentArray('categories'),
			[
				(int)$article->category
			],
			[
				'id' => 'category',
				'name' => 'category'
			])
			->append('</li></ul>')

			/* customize */

			->radio(
			[
				'id' => get_class() . '\Customize',
				'class' => 'rs-admin-fn-status-tab',
				'name' => get_class() . '\Tab'
			])
			->label($this->_language->get('customize'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => get_class() . '\Customize'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
			->label($this->_language->get('headline'),
			[
				'for' => 'headline'
			])
			->select($helperOption->getToggleArray(),
			[
				$article->id ? (int)$article->headline : 1
			],
			[
				'id' => 'headline',
				'name' => 'headline'
			])
			->append('</li><li>')
			->label($this->_language->get('byline'),
			[
				'for' => 'byline'
			])
			->select($helperOption->getToggleArray(),
			[
				$article->id ? (int)$article->byline : 1
			],
			[
				'id' => 'byline',
				'name' => 'byline'
			])
			->append('</li><li>')
			->label($this->_language->get('comments'),
			[
				'for' => 'comments'
			])
			->select($helperOption->getToggleArray(),
			[
				(int)$article->comments
			],
			[
				'id' => 'comments',
				'name' => 'comments'
			])
			->append('</li><li>')
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->select($helperOption->getVisibleArray(),
			[
				$article->id ? (int)$article->status : 1
			],
			[
				'id' => 'status',
				'name' => 'status'
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
				'value' => $article->id ? (int)$article->rank : $articleModel->query()->max('rank') + 1
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
				->select($helperOption->getAccessArray('groups'),
				(array)json_decode($article->access),
				[
					'id' => 'access',
					'name' => 'access[]',
					'multiple' => 'multiple',
					'size' => count($helperOption->getAccessArray('groups'))
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
				'value' => $article->id
			])
			->token()
			->append('<div class="rs-admin-wrapper-button">')
			->cancel();
		if ($article->id)
		{
			if ($this->_registry->get('articlesDelete'))
			{
				$formElement->delete();
			}
			if ($this->_registry->get('articlesEdit'))
			{
				$formElement->save();
			}
		}
		else if ($this->_registry->get('articlesNew'))
		{
			$formElement->create();
		}
		$formElement->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminArticleFormEnd');
		return $output;
	}
}
