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
		if ($this->_registry->get('commentReplace'))
		{
			return '<!-- commentReplace -->';
		}
		$output = Module\Hook::trigger('commentStart');
		$settingModel = new Model\Setting();

		/* html element */

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
				'class' => 'rs-js-validate rs-form-default rs-form-comment'
			],
			'button' =>
			[
				'submit' =>
				[
					'name' => self::class
				]
			]
		],
		[
			'captcha' => $settingModel->get('captcha')
		]);

		/* create the form */

		$formElement
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
		$formElement->append('</ul>');
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
