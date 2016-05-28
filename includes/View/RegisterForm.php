<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to generate the register form
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

	public function render()
	{
		$output = Hook::trigger('registerFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2', array(
				'class' => 'rs-title-content'
			))
			->text($this->_language->get('account_create'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-form rs-form-default rs-form-register'
			),
			'button' => array(
				'submit' => array(
					'name' => get_class()
				)
			)
		), array(
			'captcha' => Db::getSetting('captcha') > 0
		));

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend()
			->append('<ul><li>')
			->label('* ' . $this->_language->get('name'), array(
				'for' => 'name'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'id' => 'name',
				'name' => 'name',
				'required' => 'required'
			))
			->append('</li><li>')
			->label('* ' . $this->_language->get('user'), array(
				'for' => 'user'
			))
			->text(array(
				'id' => 'user',
				'name' => 'user',
				'required' => 'required'
			))
			->append('</li><li>')
			->label('* ' . $this->_language->get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
				'required' => 'required'
			))
			->append('</li>');
		if (Db::getSetting('captcha') > 0)
		{
			$formElement
				->append('<li>')
				->captcha('task')
				->append('</li>');
		}
		$formElement->append('</ul></fieldset>');
		if (Db::getSetting('captcha') > 0)
		{
			$formElement->captcha('solution');
		}
		$formElement
			->token()
			->submit($this->_language->get('create'));

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('registerFormEnd');
		return $output;
	}
}
