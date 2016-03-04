<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the comment form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class CommentForm implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @param integer $articleId identifier of the article
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render($articleId = null)
	{
		$output = Hook::trigger('commentFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-title-content',
		));
		$titleElement->text(Language::get('comment_new'));
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'button' => array(
				'submit' => array(
					'name' => get_class()
				)
			)
		), array(
			'captcha' => Db::getSettings('captcha') > 0
		));

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend()
			->append('<ul><li>')
			->label('* ' . Language::get('author'), array(
				'for' => 'author'
			))
			->text(array(
				'id' => 'author',
				'name' => 'author',
				'readonly' => Registry::get('myName') ? 'readonly' : null,
				'required' => 'required',
				'value' => Registry::get('myName')
			))
			->append('</li><li>')
			->label('* ' . Language::get('email'), array(
					'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
				'readonly' => Registry::get('myEmail') ? 'readonly' : null,
				'required' => 'required',
				'value' => Registry::get('myEmail')
			))
			->append('</li><li>')
			->label(Language::get('url'), array(
				'for' => 'url'
			))
			->url(array(
				'id' => 'url',
				'name' => 'url'
			))
			->append('</li><li>')
			->label('* ' . Language::get('text'), array(
				'for' => 'text'
			))
			->textarea(array(
				'class' => 'rs-js-auto-resize rs-js-editor-textarea rs-field-textarea',
				'id' => 'text',
				'name' => 'text',
				'required' => 'required'
			))
			->append('</li>');
		if (Db::getSettings('captcha') > 0)
		{
			$formElement
				->append('<li>')
				->captcha('task')
				->append('</li>');
		}
		$formElement->append('</ul></fieldset>');
		if (Db::getSettings('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->hidden(array(
				'name' => 'article',
				'value' => $articleId
			))
			->token()
			->submit()
			->reset();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('commentFormEnd');
		return $output;
	}
}
