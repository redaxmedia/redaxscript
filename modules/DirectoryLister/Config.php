<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Module;

/**
 * children class to store module config
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Config extends Module\Module
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected $_configArray =
	[
		'className' =>
		[
			'list' => 'rs-list-directory-lister rs-fn-clearfix',
			'link' => 'rs-link-directory-lister',
			'textSize' => 'rs-text-directory-lister rs-has-size',
			'textDate' => 'rs-text-directory-lister rs-has-date',
			'types' =>
			[
				'directory' => 'rs-is-directory',
				'directoryParent' => 'rs-is-directory rs-is-parent',
				'file' => 'rs-is-file',
				'fileText' => 'rs-is-file rs-is-text',
				'fileImage' => 'rs-is-file rs-is-image',
				'fileMusic' => 'rs-is-file rs-is-music',
				'fileVideo' => 'rs-is-file rs-is-video',
				'fileArchive' => 'rs-is-file rs-is-archive'
			]
		],
		'size' =>
		[
			'unit' => 'kB',
			'divider' => 1024
		],
		'replaceKey' =>
		[
			'extension'	=> '{extension}'
		],
		'extension' =>
		[
			'doc' => 'fileText',
			'txt' => 'fileText',
			'gif' => 'fileImage',
			'jpg' => 'fileImage',
			'pdf' => 'fileImage',
			'png' => 'fileImage',
			'mp3' => 'fileMusic',
			'wav' => 'fileMusic',
			'avi' => 'fileVideo',
			'mov' => 'fileVideo',
			'mp4' => 'fileVideo',
			'tar' => 'fileArchive',
			'rar' => 'fileArchive',
			'zip' => 'fileArchive'
		]
	];
}
