<?php
namespace Redaxscript\Admin;

use Redaxscript\Messenger as BaseMessenger;

/**
 * parent class to create a admin message
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Messenger
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Messenger extends BaseMessenger
{
	/**
	 * options of the messenger
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'box' => 'rs-admin-box-note rs-admin-fn-clearfix',
			'title' => 'rs-admin-title-note',
			'list' => 'rs-admin-list-note',
			'link' => 'rs-admin-button-note',
			'redirect' => 'rs-admin-meta-redirect',
			'note' =>
			[
				'success' => 'rs-admin-is-success',
				'warning' => 'rs-admin-is-warning',
				'error' => 'rs-admin-is-error',
				'info' => 'rs-admin-is-info'
			]
		]
	];
}