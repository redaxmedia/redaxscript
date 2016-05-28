<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;

/**
 * children class to generate the recover form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class RecoverForm extends ViewAbstract
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
		$output = Hook::trigger('recoverFormStart');

		/* html elements */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2', array(
				'class' => 'rs-title-content'
			))
			->text($this->_language->get('recovery'));
		$formElement = new Html\Form($this->_registry, $this->_language);
		$formElement->init(array(
			'form' => array(
				'class' => 'rs-js-validate-form rs-form-default rs-form-recover'
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
			->legend($this->_language->get('recovery_request') . $this->_language->get('point'))
			->append('<ul><li>')
			->label('* ' . $this->_language->get('email'), array(
				'for' => 'email'
			))
			->email(array(
				'autofocus' => 'autofocus',
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
			->submit();

		/* collect output */

		$output .= $titleElement . $formElement;
		$output .= Hook::trigger('recoverFormEnd');
		return $output;
	}
}
