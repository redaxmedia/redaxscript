<?php
namespace Redaxscript\Modules\Dialog\Admin;

use Redaxscript\Modules\Dialog\Dialog as BaseDialog;

/**
 * javascript powered admin dialog
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Dialog extends BaseDialog
{
	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'overlay' => 'rs-admin-overlay-dialog',
			'component' => 'rs-admin-component-dialog',
			'title' => 'rs-admin-title-dialog',
			'box' => 'rs-admin-box-dialog',
			'field' => 'rs-admin-field-default rs-admin-field-text',
			'button' => 'rs-admin-button-default',
			'buttonOk' => 'js-admin-ok',
			'buttonCancel' => 'js-admin-cancel'
		]
	];
}
