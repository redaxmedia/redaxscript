<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Module;

/**
 * simple contact form
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Contact extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Contact',
		'alias' => 'Contact',
		'author' => 'Redaxmedia',
		'description' => 'Simple contact form',
		'version' => '2.6.0'
	);

	/**
	 * render
	 *
	 * @since 2.6.0
	 */

	public static function render()
	{
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'className' => array(
				'form' => 'js_validate_form form_default',
				'field' => 'field_text',
				'label' => 'label',
				'button' => 'button_default',
				'submit' => 'js_submit button_default',
				'reset' => 'js_reset button_default'
			),
			'name' => 'contact'
		));
		$labelElement = new Html\Element('label', array(
			'class' => 'label'
		));
		$fieldElement = new Html\Element('input', array(
			'class' => 'field_text',
			'type' => 'text'
		));

		/* build form */

		$formElement
			->append('<ul><li>')
			->append($labelElement->copy()->attr('for', 'test1')->text('text for label'))
			->append($fieldElement->copy()->attr('id', 'test1'))
			->append('</li><li>')
			->captcha('task')
			->append('</li></ul>')
			->captcha('solution')
			->token()
			->submit()
			->reset();
		return $formElement;
	}
}