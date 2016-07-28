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
				'success' => 'rs-admin-note-success',
				'warning' => 'rs-admin-note-warning',
				'error' => 'rs-admin-note-error',
				'info' => 'rs-admin-note-info'
			]
		]
	];
}