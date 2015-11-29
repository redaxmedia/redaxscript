<?php
namespace Redaxscript\Admin\Html;

use Redaxscript\Html\Form as BaseForm;

/**
 * children class to generate a form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Html
 * @author Henry Ruhs
 */

class Form extends BaseForm
{
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
		'button' => array(
			'button' => array(
				'class' => 'rs-js-button rs-admin-button-default',
				'type' => 'button'
			),
			'reset' => array(
				'class' => 'rs-js-reset rs-admin-button-default',
				'type' => 'reset',
				'value' => 'reset'
			),
			'submit' => array(
				'class' => 'rs-js-button rs-admin-button-default',
				'type' => 'submit',
				'value' => 'submit'
			)
		),
		'input' => array(
			'checkbox' => array(
				'class' => 'rs-admin-field-checkbox',
				'type' => 'checkbox'
			),
			'datetime' => array(
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'datetime'
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
			'text' => array(
				'class' => 'rs-admin-field-default rs-admin-field-text',
				'type' => 'text'
			),
			'url' => array(
				'class' => 'rs-admin-field-default rs-admin-field-url',
				'type' => 'url'
			)
		)
	);
}
