<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate the reset form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ResetForm implements ViewInterface
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
		$output = Hook::trigger('resetFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement->init('h2', array(
			'class' => 'rs-title-content',
		));
		$titleElement->text(Language::get('password_reset'));
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-form rs-form-default rs-form-reset'
			),
			'button' => array(
				'submit' => array(
					'name' => get_class()
				)
			)
		), array(
			'captcha' => true
		));

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend()
			->append('<li><ul>')
			->captcha('task')
			->append('</li></ul></fieldset>')
			->hidden(array(
				'name' => 'password',
				'value' => Registry::get('thirdParameter')
			))
			->hidden(array(
				'name' => 'id',
				'value' => Registry::get('thirdSubParameter')
			))
			->captcha('solution')
			->token()
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('resetFormEnd');
		return $output;
	}
}
