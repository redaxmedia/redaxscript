<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the register form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class RegisterForm implements ViewInterface
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
	 * @return string
	 */

	public function render()
	{
		$output = Hook::trigger('registerFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
				'class' => 'rs-title-content',
		));
		$titleElement->text(Language::get('account_create'));
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
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
			'captcha' => Db::getSettings('captcha') > 0
		));

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend()
			->append('<ul><li>')
			->label('* ' . Language::get('name'), array(
				'for' => 'name'
			))
			->text(array(
				'autofocus' => 'autofocus',
				'id' => 'name',
				'name' => 'name',
				'required' => 'required'
			))
			->append('</li><li>')
			->label('* ' . Language::get('user'), array(
				'for' => 'user'
			))
			->text(array(
				'id' => 'user',
				'name' => 'user',
				'required' => 'required'
			))
			->append('</li><li>')
			->label('* ' . Language::get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'id' => 'email',
				'name' => 'email',
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
			->token()
			->submit(Language::get('create'));

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('registerFormEnd');
		return $output;
	}
}
