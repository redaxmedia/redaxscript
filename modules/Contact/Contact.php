<?php
namespace Redaxscript\Modules\Contact;

use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
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
	 * centerStart
	 *
	 * @since 2.6.0
	 */

	public static function centerStart()
	{
		if (Request::getPost(get_class()) === 'submit')
		{
			self::_process();
		}
	}

	/**
	 * render
	 *
	 * @since 2.6.0
	 */

	public static function render()
	{
		$formElement = new Html\Form(Registry::getInstance(), Language::getInstance());
		$formElement->init(array(
			'form' => array(
				'class' => 'js_validate_form form_default'
			),
			'label' => array(
				'class' => 'label'
			),
			'textarea' => array(
				'class' => 'js_auto_resize js_editor_textarea field_textarea'
			),
			'input' => array(
				'email' => array(
					'class' => 'field_text'
				),
				'number' => array(
					'class' => 'field_text'
				),
				'text' => array(
					'class' => 'field_text'
				),
				'url' => array(
					'class' => 'field_text'
				)
			),
			'button' => array(
				'submit' => array(
					'class' => 'js_submit button_default',
					'name' => get_class()
				),
				'reset' => array(
					'class' => 'js_reset button_default',
					'name' => get_class()
				)
			)
		));

		/* create the form */

		$formElement
			->append('<fieldset>')
			->legend()
			->append('<ul><li>')
			->label('* ' . Language::get('author'), array(
				'for' => 'author'
			))
			->text(array(
				'id' => 'author',
				'name' => 'author',
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
			->append('</li><li>')
			->label(Language::get('url'), array(
				'for' => 'email'
			))
			->url(array(
				'id' => 'url',
				'name' => 'url'
			))
			->append('</li><li>')
			->label('* ' . Language::get('message'), array(
				'for' => 'text'
			))
			->textarea(array(
				'id' => 'text',
				'name' => 'text',
				'required' => 'required'
			))
			->append('</li><li>')
			->captcha('task')
			->append('</li></ul></fieldset>')
			->captcha('solution')
			->token()
			->submit()
			->reset();
		return $formElement;
	}

	/**
	 * process
	 *
	 * @since 2.6.0
	 */

	public static function _process()
	{
		return true;
	}
}