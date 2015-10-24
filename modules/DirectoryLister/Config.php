<?php
namespace Redaxscript\Modules\DirectoryLister;

use Redaxscript\Module;

/**
 * parent class to store module config
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
	 * module config
	 *
	 * @var array
	 */

	protected static $_config = array(
		'className' => array(
			'link' => 'link_directory_lister',
			'list' => 'list_directory_lister clearfix',
			'textSize' => 'text_directory_lister text_size',
			'textDate' => 'text_directory_lister text_date',
			'types' => array(
				'directory' => 'directory',
				'directoryParent' => 'directory_parent',
				'fileBlank' => 'file_blank',
				'fileText' => 'file_text',
				'fileImage' => 'file_image',
				'fileMusic' => 'file_music',
				'fileVideo' => 'file_video',
				'fileArchive' => 'file_archive'
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
