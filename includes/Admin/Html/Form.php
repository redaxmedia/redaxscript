<?php
namespace Redaxscript\Admin\Html;

use Redaxscript\Html\Form as BaseForm;

/**
 * children class to generate a admin form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 *
 * @method create()
 * @method delete()
 * @method save()
 */

class Form extends BaseForm
{
	/**
	 * languages of the form
	 *
	 * @var array
	 */

	protected $_languageArray = array(
		'legend' => 'fields_required',
		'button' => array(
			'button' => 'ok',
			'create' => 'create',
			'reset' => 'reset',
			'save' => 'save',
			'submit' => 'submit'
		),
		'link' => array(
			'cancel' => 'cancel',
			'delete' => 'delete'
		)
	);

	/**
	 * attributes of the form
	 *
	 * @var array
	 */

	protected $_attributeArray = array(
		'form' => array(
			'class' => 'rs-js-validate-form rs-admin-form-default',
			'method' => 'post'
		),
		'legend' => array(
			'class' => 'rs-admin-legend-default'
		),
		'label' => array(
			'class' => 'rs-admin-label-default'
		),
		'select' => array(
			'class' => 'rs-admin-field-select'
		),
		'textarea' => array(
			'class' => 'rs-admin-field-textarea',
			'cols' => 100,
			'row' => 5
		),
		'input' => array(
			'checkbox' => array(
				'class' => 'rs-admin-field-checkbox',
				'type' => 'checkbox'
			),
			'color' => array(
				'class' => 'rs-admin-field-color',
				'type' => 'color'
			),
			'date' => array(
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'date'
			),
			'datetime' => array(
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'datetime-local'
			),
			'email' => array(
				'class' => 'rs-admin-field-default rs-admin-field-email',
				'type' => 'email'
			),
			'file' => array(
				'class' => 'rs-admin-field-file',
				'type' => 'file'
			),
			'hidden' => array(
				'class' => 'rs-admin-field-hidden',
				'type' => 'hidden'
			),
			'number' => array(
				'class' => 'rs-admin-field-default rs-admin-field-number',
				'type' => 'number'
			),
			'password' => array(
				'class' => 'rs-admin-field-default rs-admin-field-password',
				'type' => 'password'
			),
			'radio' => array(
				'class' => 'rs-admin-field-radio',
				'type' => 'radio'
			),
			'range' => array(
				'class' => 'rs-admin-field-range',
				'type' => 'range'
			),
			'search' => array(
				'class' => 'rs-js-search rs-admin-field-search',
				'type' => 'search'
			),
			'tel' => array(
				'class' => 'rs-admin-field-default rs-admin-field-tel',
				'type' => 'tel'
			),
			'time' => array(
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'time'
			),
			'text' => array(
				'class' => 'rs-admin-field-default rs-admin-field-default',
				'type' => 'text'
			),
			'url' => array(
				'class' => 'rs-admin-field-default rs-admin-field-url',
				'type' => 'url'
			),
			'week' => array(
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'week'
			)
		),
		'button' => array(
			'button' => array(
				'class' => 'rs-js-button rs-admin-button-default rs-admin-button-large',
				'type' => 'button'
			),
			'reset' => array(
				'class' => 'rs-js-reset rs-admin-button-default rs-admin-button-reset rs-admin-button-large',
				'type' => 'reset'
			),
			'submit' => array(
				'class' => 'rs-js-submit rs-admin-button-default rs-admin-button-submit rs-admin-button-large',
				'type' => 'submit',
				'value' => 'submit'
			),
			'save' => array(
				'class' => 'rs-js-save rs-admin-button-default rs-admin-button-save rs-admin-button-large',
				'name' => 'edit',
				'type' => 'submit',
				'value' => 'save'
			),
			'create' => array(
				'class' => 'rs-js-create rs-admin-button-default rs-admin-button-create rs-admin-button-large',
				'name' => 'new',
				'type' => 'submit',
				'value' => 'create'
			)
		),
		'link' => array(
			'cancel' => array(
				'class' => 'rs-js-cancel rs-admin-button-default rs-admin-button-cancel rs-admin-button-large',
				'href' => 'javascript:history.back()'
			),
			'delete' => array(
				'class' => 'rs-js-delete rs-admin-button-default rs-admin-button-delete rs-admin-button-large'
			)
		)
	);
}
