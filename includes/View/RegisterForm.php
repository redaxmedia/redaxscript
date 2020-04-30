<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;

/**
 * children class to create the register form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class RegisterForm extends ViewAbstract
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
		$output = Module\Hook::trigger('registerFormStart');
		$settingModel = new Model\Setting();
		$userValidator = new Validator\User();
		$passwordValidator = new Validator\Password();

		/* html element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2',
			[
				'class' => 'rs-title-content'
			])
			->text($this->_language->get('account_create'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(
		[
			'form' =>
			[
				'class' => 'rs-js-validate rs-form-default rs-form-register'
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
			->label('* ' . $this->_language->get('name'),
			[
				'for' => 'name'
			])
			->text(
			[
				'autofocus' => 'autofocus',
				'id' => 'name',
				'name' => 'name',
				'required' => 'required'
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('user'),
			[
				'for' => 'user'
			])
			->text(
			[
				'id' => 'user',
				'name' => 'user',
				'pattern' => $userValidator->getFormPattern(),
				'required' => 'required'
			])
			->append('</li><li>')
			->label('* ' . $this->_language->get('password'),
			[
				'for' => 'password'
			])
			->password(
			[
				'autocomplete' => 'new-password',
				'id' => 'password',
				'name' => 'password',
				'pattern' => $passwordValidator->getFormPattern(),
				'required' => 'required'
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
			->submit($this->_language->get('create'));

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Module\Hook::trigger('registerFormEnd');
		return $output;
	}
}
