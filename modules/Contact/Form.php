<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\View\ViewAbstract;

/**
 * children class to create the contact form
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Form extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$settingModel = new Model\Setting();
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
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
			->label('* ' . $this->_language->get('message'),
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
			->token()
			->submit()
			->reset();
		return $formElement;
	}
}
