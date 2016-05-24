<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to generate the reset form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class ResetForm extends ViewAbstract implements ViewInterface
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
		$output = Hook::trigger('resetFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2', array(
				'class' => 'rs-title-content'
			))
			->text($this->_language->get('password_reset'));
		$formElement = new Html\Form($this->_registry, $this->_language);
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
				'value' => $this->_registry->get('thirdParameter')
			))
			->hidden(array(
				'name' => 'id',
				'value' => $this->_registry->get('thirdSubParameter')
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
