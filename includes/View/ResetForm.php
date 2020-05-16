<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the reset form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ResetForm extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('resetFormStart');
		$passwordValidator = new Validator\Password();

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('password_reset'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate rs-form-default rs-form-reset'
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
			'captcha' => 1
		]);

		/* create the form */

		$formElement
			->legend()
			->append('<ul><li>')
			->label($this->_language->get('password_new'),
			[
				'for' => 'password'
			])
			->password(
			[
				'autocomplete' => 'new-password',
				'id' => 'password',
				'name' => 'password',
				'pattern' => $passwordValidator->getPattern(),
				'required' => 'required'
			])
			->append('</li><li>')
			->captcha('task')
			->append('</li></ul>')
			->hidden(
			[
				'name' => 'id',
				'value' => $this->_registry->get('thirdSubParameter')
			])
			->hidden(
			[
				'name' => 'password-hash',
				'value' => $this->_registry->get('thirdParameter')
			])
			->captcha('solution')
			->token()
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('resetFormEnd');
		return $output;
	}
}
