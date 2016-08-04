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
			'box' => 'rs-admin-box-messenger rs-admin-box-note',
			'title' => 'rs-admin-title-messenger rs-admin-title-note',
			'list' => 'rs-admin-list-messenger',
			'link' => 'rs-admin-button-default rs-admin-button-messenger',
			'redirect' => 'rs-admin-meta-redirect',
			'notes' =>
			[
				'success' => 'rs-admin-is-success',
				'warning' => 'rs-admin-is-warning',
				'error' => 'rs-admin-is-error',
				'info' => 'rs-admin-is-info'
			]
		]
	];
}