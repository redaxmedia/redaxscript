<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin\Html\Form as AdminForm;
use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to create the article form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class CommentForm extends ViewAbstract implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param integer $commentId identifier of the comment
	 *
	 * @return string
	 */

	public function render($commentId = null)
	{
		$output = Hook::trigger('adminCommentFormStart');
		$comment = Db::forTablePrefix('comments')->whereIdIs($commentId)->findOne();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2',
		[
			'class' => 'rs-admin-title-content',
		]);
		$titleElement->text($comment->author ? $comment->author : $this->_language->get('comment_new'));
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
				'action' => $this->_registry->get('parameterRoute') . ($comment->id ? 'admin/process/comments/' . $comment->id : 'admin/process/comments'),
				'class' => 'rs-admin-js-tab rs-admin-js-validate-form rs-admin-component-tab rs-admin-form-default rs-admin-fn-clearfix'
			],
			'link' =>
			[
				'cancel' =>
				[
					'href' => $this->_registry->get('commentsEdit') && $this->_registry->get('commentsDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/comments' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'delete' =>
				[
					'href' => $comment->id ? $this->_registry->get('parameterRoute') . 'admin/delete/comments/' . $comment->id . '/' . $this->_registry->get('token') : null
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
				->text($this->_language->get('comment'))
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
			->label('* ' . $this->_language->get('author'),
			[
				'for' => 'author'
			])
			->text(
			[
				'id' => 'author',
				'name' => 'author',
				'readonly' => 'readonly',
				'required' => 'required',
				'value' => $comment->author ? $comment->author : $this->_registry->get('myName')
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('email'),
			[
				'for' => 'email'
			])
			->email(
			[
				'id' => 'email',
				'name' => 'email',
				'readonly' => 'readonly',
				'required' => 'required',
				'value' => $comment->email ? $comment->email : $this->_registry->get('myEmail')
			])
			->append('</li><li>')
			->label($this->_language->get('url'),
			[
				'for' => 'url'
			])
			->url(
			[
				'id' => 'url',
				'name' => 'url',
				'value' => $comment->url
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('text'),
			[
				'for' => 'text'
			])
			->textarea(
			[
				'id' => 'text',
				'name' => 'text',
				'required' => 'required',
				'value' => htmlspecialchars($comment->text)
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
				'value' => $comment->language
			])
			->append('</li><li>')
			->label($this->_language->get('article'),
			[
				'for' => 'article'
			])
			->select(Helper\Option::getContentArray('articles'),
			[
				'id' => 'article',
				'name' => 'article',
				'value' => intval($comment->article)
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
				'value' => $comment->id ? intval($comment->status) : 1
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
				'value' => $comment->id ? intval($comment->rank) : Db::forTablePrefix('comments')->max('rank') + 1
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
					'value' => $comment->access
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
				'value' => $comment->date ? $comment->date : null
			])
			->append('</li></ul></fieldset></div>')
			->token()
			->cancel();
		if ($comment->id)
		{
			if ($this->_registry->get('commentsDelete'))
			{
				$formElement->delete();
			}
			if ($this->_registry->get('commentsEdit'))
			{
				$formElement->save();
			}
		}
		else if ($this->_registry->get('commentsNew'))
		{
			$formElement->create();
		}

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('adminCommentFormEnd');
		return $output;
	}
}
