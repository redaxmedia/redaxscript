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
				'button' => 'button_default',
				'submit' => 'js_submit button_default',
				'reset' => 'js_reset button_default'
			),
			'name' => 'contact'
		));
		$formElement
			->append('<li><input class="field_note field_text" /></li>')
			->wrapInner('ul')
			->token()
			->submit()
			->reset();
		return $formElement;
	}
}