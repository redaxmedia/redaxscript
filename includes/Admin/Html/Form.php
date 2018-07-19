<?php
namespace Redaxscript\Admin\Html;

use Redaxscript\Html\Form as BaseForm;

/**
 * children class to create a admin form
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Html
 * @author Henry Ruhs
 *
 * @method $this create(string $text = null, array $attributeArray = [])
 * @method $this delete(string $text = null, array $attributeArray = [])
 * @method $this save(string $text = null, array $attributeArray = [])
 * @method $this uninstall(string $text = null, array $attributeArray = [])
 */

class Form extends BaseForm
{
	/**
	 * languages of the form
	 *
	 * @var array
	 */

	protected $_languageArray =
	[
		'legend' => 'fields_required',
		'button' =>
		[
			'button' => 'ok',
			'create' => 'create',
			'reset' => 'reset',
			'save' => 'save',
			'submit' => 'submit'
		],
		'link' =>
		[
			'cancel' => 'cancel',
			'delete' => 'delete',
			'uninstall' => 'uninstall'
		]
	];

	/**
	 * attributes of the form
	 *
	 * @var array
	 */

	protected $_attributeArray =
	[
		'form' =>
		[
			'class' => 'rs-admin-js-validate-form rs-admin-form-default',
			'method' => 'post'
		],
		'legend' =>
		[
			'class' => 'rs-admin-legend-default'
		],
		'label' =>
		[
			'class' => 'rs-admin-label-default'
		],
		'select' =>
		[
			'class' => 'rs-admin-field-select'
		],
		'textarea' =>
		[
			'class' => 'rs-admin-field-textarea',
			'cols' => 100,
			'rows' => 5
		],
		'input' =>
		[
			'checkbox' =>
			[
				'class' => 'rs-admin-field-checkbox',
				'type' => 'checkbox'
			],
			'color' =>
			[
				'class' => 'rs-admin-field-color',
				'type' => 'color'
			],
			'date' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'date'
			],
			'datetime' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'datetime-local'
			],
			'email' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-email',
				'type' => 'email'
			],
			'file' =>
			[
				'class' => 'rs-admin-field-file',
				'type' => 'file'
			],
			'hidden' =>
			[
				'class' => 'rs-admin-field-hidden',
				'type' => 'hidden'
			],
			'number' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-number',
				'type' => 'number'
			],
			'password' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-password',
				'type' => 'password'
			],
			'radio' =>
			[
				'class' => 'rs-admin-field-radio',
				'type' => 'radio'
			],
			'range' =>
			[
				'class' => 'rs-admin-field-range',
				'type' => 'range'
			],
			'search' =>
			[
				'class' => 'rs-admin-js-search rs-admin-field-search',
				'type' => 'search'
			],
			'tel' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-tel',
				'type' => 'tel'
			],
			'time' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'time'
			],
			'text' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-default',
				'type' => 'text'
			],
			'url' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-url',
				'type' => 'url'
			],
			'week' =>
			[
				'class' => 'rs-admin-field-default rs-admin-field-date',
				'type' => 'week'
			]
		],
		'button' =>
		[
			'button' =>
			[
				'class' => 'rs-admin-js-button rs-admin-button-default rs-admin-button-large',
				'type' => 'button'
			],
			'reset' =>
			[
				'class' => 'rs-admin-js-reset rs-admin-button-default rs-admin-button-reset rs-admin-button-large',
				'type' => 'reset'
			],
			'submit' =>
			[
				'class' => 'rs-admin-js-submit rs-admin-button-default rs-admin-button-submit rs-admin-button-large',
				'type' => 'submit',
				'value' => 'submit'
			],
			'save' =>
			[
				'class' => 'rs-admin-js-save rs-admin-button-default rs-admin-button-save rs-admin-button-large',
				'name' => 'edit',
				'type' => 'submit',
				'value' => 'update'
			],
			'create' =>
			[
				'class' => 'rs-admin-js-create rs-admin-button-default rs-admin-button-create rs-admin-button-large',
				'name' => 'new',
				'type' => 'submit',
				'value' => 'create'
			]
		],
		'link' =>
		[
			'cancel' =>
			[
				'class' => 'rs-admin-js-cancel rs-admin-button-default rs-admin-button-cancel rs-admin-button-large',
				'href' => 'javascript:history.back()'
			],
			'delete' =>
			[
				'class' => 'rs-admin-js-delete rs-admin-button-default rs-admin-button-delete rs-admin-button-large'
			],
			'uninstall' =>
			[
				'class' => 'rs-admin-js-uninstall rs-admin-button-default rs-admin-button-uninstall rs-admin-button-large'
			]
		]
	];
}
