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

class Config extends Module
{
	/**
	 * array of config
	 *
	 * @var array
	 */

	protected static $_configArray = array(
		'className' => array(
			'list' => 'rs-list-directory-lister rs-clearfix',
			'link' => 'rs-link-directory-lister',
			'textSize' => 'rs-text-directory-lister rs-text-size',
			'textDate' => 'rs-text-directory-lister rs-text-date',
			'types' => array(
				'directory' => 'rs-directory',
				'directoryParent' => 'rs-directory-parent',
				'fileBlank' => 'rs-file-blank',
				'fileText' => 'rs-file-text',
				'fileImage' => 'rs-file-image',
				'fileMusic' => 'rs-file-music',
				'fileVideo' => 'rs-file-video',
				'fileArchive' => 'rs-file-archive'
			)
		),
		'size' => array(
			'unit' => 'kB',
			'divider' => 1024
		),
		'replaceKey' => array(
			'extension'	=> '{extension}'
		),
		'extension' => array(
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
		)
	);
}
