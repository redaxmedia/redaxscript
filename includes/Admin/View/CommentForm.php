<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
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

class CommentForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @param int $commentId identifier of the comment
	 *
	 * @return string
	 */

	public function render(int $commentId = null) : string
	{
		$output = Module\Hook::trigger('adminCommentFormStart');
		$commentModel = new Admin\Model\Comment();
		$comment = $commentModel->getById($commentId);
		$helperOption = new Helper\Option($this->_language);

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($comment->author ? $comment->author : $this->_language->get('comment_new'));
		$formElement = new Admin\Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-admin-js-validate-form rs-admin-fn-tab rs-admin-component-tab rs-admin-form-default'
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
					'href' => $this->_registry->get('commentsEdit') && $this->_registry->get('commentsDelete') ? $this->_registry->get('parameterRoute') . 'admin/view/comments' : $this->_registry->get('parameterRoute') . 'admin'
				],
				'delete' =>
				[
					'href' => $comment->id ? $this->_registry->get('parameterRoute') . 'admin/delete/comments/' . $comment->id . '/' . $this->_registry->get('token') : null
				]
			]
		]);

		/* create the form */

		$formElement

			/* comment */

			->radio(
			[
				'id' => get_class() . '\Comment',
				'class' => 'rs-admin-fn-status-tab',
				'name' => get_class() . '\Tab',
				'checked' => 'checked'
			])
			->label($this->_language->get('comment'),
			[
				'class' => 'rs-admin-fn-toggle-tab rs-admin-label-tab',
				'for' => get_class() . '\Comment'
			])
			->append('<ul class="rs-admin-fn-content-tab rs-admin-box-tab"><li>')
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
				'value' => $comment->language
			],
			[
				'id' => 'language',
				'name' => 'language'
			])
			->append('</li><li>')
			->label($this->_language->get('article'),
			[
				'for' => 'article'
			])
			->select($helperOption->getContentArray('articles'),
			[
				(int)$comment->article
			],
			[
				'id' => 'article',
				'name' => 'article'
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
			->label($this->_language->get('status'),
			[
				'for' => 'status'
			])
			->select($helperOption->getVisibleArray(),
			[
				$comment->id ? (int)$comment->status : 1
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
				'value' => $comment->id ? (int)$comment->rank : $commentModel->query()->max('rank') + 1
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
				[
					$comment->access
				],
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
				'value' => $comment->date ? $comment->date : null
			])
			->append('</li></ul>')
			->hidden(
			[
				'name' => 'id',
				'value' => $comment->id
			])
			->token()
			->append('<div class="rs-admin-wrapper-button">')
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
		$formElement->append('</div>');

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('adminCommentFormEnd');
		return $output;
	}
}
