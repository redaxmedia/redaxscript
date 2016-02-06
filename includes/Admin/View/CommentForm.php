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

class CommentForm implements ViewInterface
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
		$titleElement->init('h2', array(
			'class' => 'rs-admin-title-content',
		));
		$titleElement->text($comment->title ? $comment->title : Language::get('comment_new'));
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
				'action' => 'admin/process/comments',
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
				'href' => 'admin/view/comments'
			))
			->text(Language::get('cancel'));
		if ($comment->id)
		{
			$linkDelete = new Html\Element();
			$linkDelete
				->init('a', array(
					'class' => 'rs-js-delete rs-js-confirm rs-admin-button-default rs-admin-button-delete rs-admin-button-large',
					'href' => 'admin/delete/comments/' . $comment->id . '/' . Registry::get('token')
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
				->text(Language::get('comment'))
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
			->label('* ' . Language::get('author'), array(
				'for' => 'author'
			))
			->text(array(
				'id' => 'author',
				'name' => 'author',
				'readonly' => 'readonly',
				'required' => 'required',
				'value' => $comment->author ? $comment->author : Registry::get('myName')
			))
			->append('</li><li>')
			->label('* ' . Language::get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
				'readonly' => 'readonly',
				'required' => 'required',
				'value' => $comment->email ? $comment->email : Registry::get('myEmail')
			))
			->append('</li><li>')
			->label(Language::get('url'), array(
				'for' => 'url'
			))
			->url(array(
				'id' => 'url',
				'name' => 'url',
				'value' => $comment->url
			))
			->append('</li><li>')
			->label('* ' . Language::get('text'), array(
				'for' => 'text'
			))
			->textarea(array(
				'id' => 'text',
				'name' => 'text',
				'required' => 'required',
				'value' => htmlspecialchars($comment->text)
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
				'value' => $comment->language
			))
			->append('</li><li>')
			->label(Language::get('article'), array(
				'for' => 'article'
			))
			->select(Helper\Option::getContentArray('articles'), array(
				'id' => 'article',
				'name' => 'article',
				'value' => $comment->article
			))
			->append('</li></ul></fieldset>')

			/* last tab */

			->append('<fieldset id="tab-3" class="rs-js-set-tab rs-set-tab"><ul><li>')
			->label(Language::get('status'), array(
				'for' => 'status'
			))
			->select(Helper\Option::getVisibleArray(), array(
				'id' => 'status',
				'name' => 'status',
				'value' => $comment->status
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
				'value' => $comment->access
			))
			->append('</li><li>')
			->label(Language::get('date'), array(
				'for' => 'date'
			))
			->datetime(array(
				'id' => 'date',
				'name' => 'date',
				'value' => date('Y-m-d\TH:i:s', strtotime($comment->date))
			))
			->append('</li></ul></fieldset></div>')
			->token()
			->append($linkCancel);
		if ($comment->id)
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
		$output .= Hook::trigger('adminCommentFormEnd');
		return $output;
	}
}
