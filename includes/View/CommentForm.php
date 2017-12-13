<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;

/**
 * children class to create the comment form
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
	 * @param int $articleId identifier of the article
	 *
	 * @return string
	 */

	public function render(int $articleId = null) : string
	{
		$output = Module\Hook::trigger('commentFormStart');
		$settingModel = new Model\Setting();

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('comment_new'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate-form rs-form-default rs-form-comment'
			],
			'button' =>
			[
				'submit' =>
				[
					'name' => get_class()
				]
			]
		],
		[
			'captcha' => $settingModel->get('captcha') > 0
		]);

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend()
			->append('<ul><li>')
			->label('* ' . $this->_language->get('author'),
			[
				'for' => 'author'
			])
			->text(
			[
				'id' => 'author',
				'name' => 'author',
				'readonly' => $this->_registry->get('myName') ? 'readonly' : null,
				'required' => 'required',
				'value' => $this->_registry->get('myName')
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
				'readonly' => $this->_registry->get('myEmail') ? 'readonly' : null,
				'required' => 'required',
				'value' => $this->_registry->get('myEmail')
			])
			->append('</li><li>')
			->label($this->_language->get('url'),
			[
				'for' => 'url'
			])
			->url(
			[
				'id' => 'url',
				'name' => 'url'
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('text'),
			[
				'for' => 'text'
			])
			->textarea(
			[
				'class' => 'rs-js-auto-resize rs-js-editor-textarea rs-field-textarea',
				'id' => 'text',
				'name' => 'text',
				'required' => 'required'
			])
			->append('</li>');
		if ($settingModel->get('captcha') > 0)
		{
			$formElement
				->append('<li>')
				->captcha('task')
				->append('</li>');
		}
		$formElement->append('</ul></fieldset>');
		if ($settingModel->get('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->hidden(
			[
				'name' => 'article',
				'value' => $articleId
			])
			->token()
			->submit()
			->reset();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('commentFormEnd');
		return $output;
	}
}
